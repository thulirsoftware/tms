<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Support\Carbon;
use Session;
use App\User;
use App\Task;
use App\InternTask;
use App\RegularTask;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use Illuminate\Http\Request;
use App\Leave;
use App\EmployeesLeave;
use App\Notifications\ApproveReminder;
use App\Notifications\DeclinedReminder;
use App\Notifications\LeaveReminder;
use DB;

use App\Notifications\TaskReminder;

class AjaxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $employees = Employee::where('empStatus', null)->get();
        $designations = CfgDesignations::getDesignation();
        return view('admin.task.index', compact('employees', 'designations'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function setStartTime(Request $request)
    {
        // Determine if the user is an intern
        $isIntern = Auth::user()->type === 'intern';

        // Fetch the task using the appropriate model
        if ($request->taskType == 'regular') {
            if ($isIntern) {
                $regularTask = RegularTask::find($request->taskId); // Use InternRegularTask for interns
                $task = new InternTask; // Use InternTask for interns
            } else {
                $regularTask = RegularTask::find($request->taskId);
                $task = new Task;
            }

            $regularTask->takenDate = date('Y-m-d');
            $regularTask->save();

            $task->regularTaskId = $regularTask->id;
            $task->assignedDate = $regularTask->assignedDate;
            $task->assignedBy = $regularTask->assignedBy;
            $task->projectId = $regularTask->projectId;
            $task->activityId = $regularTask->activityId;
            $task->empId = $regularTask->empId;
            $task->instruction = $regularTask->instruction;
            $task->priority = $regularTask->priority;
            $task->approval = $regularTask->approval;
            $task->status = 1; // Initially Assigned
        } else {
            $task = $isIntern ? InternTask::find($request->taskId) : Task::find($request->taskId);
        }

        if ($task->status != 1 || $task->endTime != null) {
            $newTask = $task->replicate();
            $newTask->takenDate = date('Y-m-d');
            $newTask->relatedTaskId = $task->relatedTaskId;
            $newTask->comment = null;
            $newTask->endTime = null;
            $newTask->status = 2; // In Progress
            $newTask->startTime = $request->time;
            $newTask->hours = null;
            $newTask->minutes = null;
            $newTask->save();
            return ['status' => true];
        }

        $task->takenDate = date('Y-m-d');
        $task->status = 2; // Set as In Progress
        $task->startTime = $request->time;
        $task->comment = null;
        $task->endTime = null;
        $task->hours = null;
        $task->minutes = null;
        $task->save();

        if ($request->taskType == 'regular') {
            $task->relatedTaskId = $task->id;
            $task->save();
        }

        return ['status' => true];
    }

    public function setEndTime(Request $request)
    {
        // Determine if the user is an intern
        $isIntern = Auth::user()->type === 'intern';

        // Fetch the task using the appropriate model
        $task = $isIntern ? InternTask::find($request->taskId) : Task::find($request->taskId);
        $endTimeFormatted = $request->time;
        if ($task->takenDate != date('Y-m-d')) {
            $tasksInDate = $isIntern ? InternTask::where('takenDate', $task->takenDate)
                ->where('endTime', '!=', null)
                ->get() :
                Task::where('takenDate', $task->takenDate)
                ->where('endTime', '!=', null)
                ->get();
            $totalSeconds = 0;

            foreach ($tasksInDate as $t) {

                 list($sh, $sm, $ss) = explode(':', $t->startTime);
                $startSeconds = ($sh * 3600) + ($sm * 60) + $ss;

                 list($eh, $em, $es) = explode(':', $t->endTime);
                $endSeconds = ($eh * 3600) + ($em * 60) + $es;

                 if ($endSeconds < $startSeconds) {
                    $endSeconds += 24 * 3600;
                }

                 $totalSeconds += ($endSeconds - $startSeconds);
            }
            $tasksInEndTimeNull = $isIntern ? InternTask::where('takenDate', $task->takenDate)
                ->where('endTime', '=', null)
                ->first() :
                Task::where('takenDate', $task->takenDate)
                ->where('endTime', '=', null)
                ->first();
            $diffInHours = 0;
            if ($tasksInEndTimeNull) {
                $date = $task->takenDate; // YYYY-MM-DD
                $start = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $task->startTime);
                $now   =  Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $request->time);

                $diffInHours = $start->diffInHours($now);
            }

            // Convert seconds → hours
            $totalHours = $totalSeconds / 3600;
            $working_hours = $totalHours + $diffInHours;



            // Check if more than 8 hrs
            $moreThan8 = $working_hours > 8 ? 1 : 0;

            if ($moreThan8) {
                $remaining_hours_add = 8 - $totalHours;
                $startTime = Carbon::createFromFormat('H:i:s', $task->startTime);
                $endTime = $startTime->copy()->addHours(floor($remaining_hours_add))
                    ->addMinutes(($remaining_hours_add - floor($remaining_hours_add)) * 60);

                $endTimeFormatted = $endTime->format('H:i:s');

                $startTime = Carbon::createFromFormat('H:i:s', $task->startTime);
                $endTime = Carbon::createFromFormat('H:i:s', $endTimeFormatted);

                // Calculate difference in total minutes
                if ($endTime->lessThanOrEqualTo($startTime)) {
                    $endTime->addDay();
                }

                // Calculate difference
                $diffInMinutes = $endTime->diffInMinutes($startTime);

                $hours = intdiv($diffInMinutes, 60);
                $minutes = $diffInMinutes % 60;
            } else {
                $etime = explode(':', $request->time);
                $stime = explode(':', $task->startTime);

                $allMinutes = (($etime[0] * 60) + $etime[1]) - (($stime[0] * 60) + $stime[1]);
                $hours = str_pad(intval($allMinutes / 60), 2, "0", STR_PAD_LEFT);
                $minutes = str_pad(intval($allMinutes % 60), 2, "0", STR_PAD_LEFT);
                $moreThan8 = 0;
            }
        } else {
            $etime = explode(':', $request->time);
            $stime = explode(':', $task->startTime);

            $allMinutes = (($etime[0] * 60) + $etime[1]) - (($stime[0] * 60) + $stime[1]);
            $hours = str_pad(intval($allMinutes / 60), 2, "0", STR_PAD_LEFT);
            $minutes = str_pad(intval($allMinutes % 60), 2, "0", STR_PAD_LEFT);
            $moreThan8 = 0;
        }
        
         
        $task->comment = $request->taskComment;
        $task->status = $request->taskStatus;
        $task->endTime = $endTimeFormatted;
        $task->is_more_than_8 = $moreThan8;
        $task->end_updated_time = $request->time;
        $task->hours = $hours;
        $task->minutes = $minutes;
        $task->save();

        return ['status' => true];
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approveTask(Request $request)
    {
        // Determine if the user is an intern
        $isIntern = Auth::user()->type === 'intern';

        // Fetch the task using the appropriate model
        if ($request->type == '') {
            // Use InternTask for interns and Task for others
            $task = $isIntern ? InternTask::find($request->taskId) : Task::find($request->taskId);
        } else {
            // RegularTask is common for both Task and InternTask
            $task = RegularTask::find($request->taskId);
        }

        // If task not found in the first model, fall back to RegularTask
        if ($task === null) {
            $task = RegularTask::find($request->taskId);
        }

        // If task is found
        if ($task) {
            $task->approval = 'yes';
            $task->save();

            // Fetch the employee and user
            $employee = Employee::find($task->empId);
            $user = User::where('empId', $employee->empId)->first();
            $user->notify(new TaskReminder($task, $employee, 'approve a task'));

            return ['status' => true, 'approval' => 'yes'];
        }

        return ['status' => false];
    }

    public function declineTask(Request $request)
    {
        // Determine if the user is an intern
        $isIntern = Auth::user()->type === 'intern';

        // Fetch the task using the appropriate model
        if ($request->type == '') {
            // Use InternTask for interns and Task for others
            $task = $isIntern ? InternTask::find($request->taskId) : Task::find($request->taskId);
        } else {
            // RegularTask is common for both Task and InternTask
            $task = RegularTask::find($request->taskId);
        }

        // If task not found in the first model, fall back to RegularTask
        if ($task === null) {
            $task = RegularTask::find($request->taskId);
        }

        // If task is found
        if ($task) {
            $task->approval = 'no';
            $task->save();

            // Fetch the employee and user
            $employee = Employee::find($task->empId);
            $user = User::where('empId', $employee->empId)->first();
            $user->notify(new TaskReminder($task, $employee, 'decline a task'));

            return ['status' => true, 'approval' => 'no'];
        }

        return ['status' => false];
    }

    public function setInterrupt(Request $request)
    {
        $isIntern = Auth::user()->type === 'intern';
        $taskModel = $isIntern ? InternTask::class : Task::class;

        $employee = Employee::where('empId', Auth::user()->empId)->first();
        if ($employee != null) {
            // Find the current task for the employee
            $task = $taskModel::where('empId', $employee->id)->where('status', 2)->first();

            if ($task != null) {
                $checkType = CfgActivity::find($task->activityId);
                if ($checkType->type == 'INT') {
                    $task->comment = 'Completed due to ' . $request->interruptFor;
                    $task->status = 4; // Completed
                } else {
                    $task->comment = 'Paused due to ' . $request->interruptFor;
                    $task->status = 3; // Paused
                }

                $task->endTime = $request->time;
                $etime = explode(':', $request->time);
                $stime = explode(':', $task->startTime);
                $allMinutes = (($etime[0] * 60) + $etime[1]) - (($stime[0] * 60) + $stime[1]);
                $task->hours = str_pad(intval($allMinutes / 60), 2, "0", STR_PAD_LEFT);
                $task->minutes = str_pad(intval($allMinutes % 60), 2, "0", STR_PAD_LEFT);
                $task->save();
            }

            // Create a new interrupt task
            $newTask = new $taskModel;
            $newTask->assignedDate = date('Y-m-d');
            $newTask->takenDate = date('Y-m-d');
            $newTask->assignedBy = Auth::user()->id;
            $newTask->projectId = 72;
            if ($request->interruptFor == 'meeting') {
                $newTask->activityId = 2;
            } elseif ($request->interruptFor == 'lunch') {
                $newTask->activityId = 1;
            } elseif ($request->interruptFor == 'break') {
                $newTask->activityId = 3;
            }
            $newTask->instruction = 'Start ' . ucfirst($request->interruptFor);
            $newTask->priority = 1;
            $newTask->startTime = $request->time;
            $newTask->empId = $employee->id;
            $newTask->comment = null;
            $newTask->endTime = null;
            $newTask->status = 2; // In Progress
            $newTask->approval = 'yes';
            $newTask->save();
            $newTask->relatedTaskId = $newTask->id;
            $newTask->save();
        }

        return ['status' => true];
    }

    public function SetMeetingInterrupt(Request $request)
    {
        $isIntern = Auth::user()->type === 'intern';
        $taskModel = $isIntern ? InternTask::class : Task::class;

        $employee = Employee::where('empId', Auth::user()->empId)->first();
        if ($employee != null) {
            // Find the current task for the employee
            $task = $taskModel::where('empId', $employee->id)->where('status', 2)->first();

            if ($task != null) {
                $checkType = CfgActivity::find($task->activityId);

                if ($checkType->type == 'INT') {
                    $task->comment = 'Completed due to ' . $request->interruptFor;
                    $task->status = 4; // Completed
                } else {
                    $task->comment = 'Paused due to ' . $request->interruptFor;
                    $task->status = 3; // Paused
                }

                // Always work in H:i:s
                $endTime = Carbon::createFromFormat('H:i:s', $request->time);
                $startTime = Carbon::createFromFormat('H:i:s', $task->startTime);

                $diffMinutes = $endTime->diffInMinutes($startTime);

                $task->endTime = $endTime->format('H:i:s');
                $task->hours = str_pad(intval($diffMinutes / 60), 2, "0", STR_PAD_LEFT);
                $task->minutes = str_pad($diffMinutes % 60, 2, "0", STR_PAD_LEFT);
                $task->save();
            }

            // Create a new interrupt task
            $newTask = new $taskModel;
            $newTask->assignedDate = date('Y-m-d');
            $newTask->takenDate = date('Y-m-d');
            $newTask->assignedBy = Auth::user()->id;
            $newTask->projectId = $request->projectId;

            if ($request->interruptFor == 'meeting') {
                $newTask->activityId = 2;
            } elseif ($request->interruptFor == 'lunch') {
                $newTask->activityId = 1;
            } elseif ($request->interruptFor == 'break') {
                $newTask->activityId = 3;
            }

            $newTask->instruction = 'Start ' . ucfirst($request->interruptFor);
            $newTask->priority = 1;
            $newTask->startTime = Carbon::createFromFormat('H:i:s', $request->time)->format('H:i:s');
            $newTask->empId = $employee->id;
            $newTask->comment = null;
            $newTask->endTime = null;
            $newTask->status = 2; // In Progress
            $newTask->approval = 'yes';
            $newTask->save();

            $newTask->relatedTaskId = $newTask->id;
            $newTask->save();
        }

        return ['status' => true];
    }

    public function setFilter(Request $request)
    {
        Session::put('task.filter', $request->all());
        $flag = 0;
        foreach ($request->all() as $key => $value) {
            if ($value != null) {
                $flag = 1;
                break;
            }
        }
        if ($flag == 0) {
            Session::forget('task.filter');
        }
        if (Auth::user()->type == 'admin') {
            return ['status' => true, 'user' => 'admin'];
        }
        return ['status' => true, 'user' => 'employee'];
    }

    public function resetFilter(Request $request)
    {
        Session::forget('task.filter');
        return ['status' => true];
    }

    /**
     * Checking Any New Notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNewNotification(Request $request)
    {
        $oldCount = Session::get('notificationCount');
        
        if (count(Auth::user()->unreadNotifications) > 0 && $oldCount < count(Auth::user()->unreadNotifications)) {
            Session::put('notificationCount', count(Auth::user()->unreadNotifications));
            return ['status' => true, 'count' => count(Auth::user()->unreadNotifications), 'list' => Auth::user()->unreadNotifications];
        } else {
            return ['status' => false, 'count' => $oldCount];
        }

    }


    // public function approveLeave(Request $request)
    // {
    //     if($request->leaveId != ''){
    //         $leave = Leave::find($request->leaveId);
    //         $leave->approval = 'yes';
    //         $leave->save();
    //         return ['status'=>true,$request->leaveId];    

    //     }else{
    //         return ['status'=>false];
    //     }


    // }


    public function approveLeave(Request $request)
    {
        if (!empty($request->leaveId)) {
            $leave = Leave::find($request->leaveId);
            if ($leave) {
                $leave->approval = $request->status; // 'yes' or approved
                $leave->save();

                // Recalculate total taken leave for this employee
                $totalTaken = Leave::where('empId', $request->empId)
                    ->where('approval', 'yes') // only approved leaves
                    ->sum('totalLeaveDays');

                $takenleave = EmployeesLeave::where('empId', $request->empId)->first();
                if ($takenleave) {
                    $takenleave->taken = $totalTaken;
                    $takenleave->save();
                }

                if($request->status == 'yes'){
                    $employee = Employee::find($request->empId);

                    $user = User::where('empId', $employee->empId)
                    ->first();

                 
                    $user->notify(new ApproveReminder($leave, $employee, 'Leave approved'));
           
                }
                 if($request->status != 'yes'){
                     $employee = Employee::find($request->empId);

                    $user = User::where('empId', $employee->empId)
                    ->first();

                    $user->notify(new DeclinedReminder($leave, $employee, 'Leave declined'));
           
                }
                
               
                

                return ['status' => true, 'leaveId' => $request->leaveId];
            }
        }
        return ['status' => false];
    }

    public function declineLeave(Request $request)
    {
        if (!empty($request->leaveId)) {
            $leave = Leave::find($request->leaveId);
            if ($leave) {
                $leave->approval = 'no';
                $leave->save();

                // Recalculate total taken leave for this employee
                $totalTaken = Leave::where('empId', $request->empId)
                    ->where('approval', 'yes') // only approved leaves
                    ->sum('totalLeaveDays');

                $takenleave = EmployeesLeave::where('empId', $request->empId)->first();
                if ($takenleave) {
                    $takenleave->taken = $totalTaken;
                    $takenleave->save();
                }
                $employee = Employee::find($request->empId);

                    $user = User::where('empId', $employee->empId)
                ->first();

                $user->notify(new DeclinedReminder($leave, $employee, 'Leave declined'));
           
                return ['status' => true, 'leaveId' => $request->leaveId];
            }
        }
        return ['status' => false];
    }




    public function declineLeave1(Request $request)
    {

        if ($request->leaveId != '') {

            $leave = Leave::find($request->leaveId);
            $leave->approval = 'no';
            $leave->save();

            return ['status' => true, $request->leaveId];


        } else {
            return ['status' => false];
        }


    }


    public function addLeave(Request $request)
    {
        if ($request->empId != '') {

            $extraLeave = EmployeesLeave::where('empId', $request->empId)->first();
            $data = $extraLeave['al'] + 1;

            $leave = DB::table('employeesleave')->where('empId', $request->empId)->update(['al' => $data]);
            return ['status' => true, $leave];

        } else {
            return ['status' => false];
        }
    }


    public function reduceLeave(Request $request)
    {
        if ($request->empId != '') {

            $extraLeave = EmployeesLeave::where('empId', $request->empId)->first();

            $data = $extraLeave['al'] - 1;

            $leave = DB::table('employeesleave')->where('empId', $request->empId)->update(['al' => $data]);
            return ['status' => true, $leave];

        } else {
            return ['status' => false];
        }
    }


    public function casualLeave(Request $request)
    {
        if ($request->empId != '' && $request->noOfMonth != '') {

            $leave = DB::table('employeesleave')->where('empId', $request->empId)->update([
                'el' => $request->noOfMonth,
            ]);
            return ['status' => true, $leave];

        } else {
            return ['status' => false];
        }
    }


    public function SetEmployeeLeave(Request $request)
    {

    }



    public function addCL(Request $request)
    {
        if (!empty($request->empId)) {
            $leave = EmployeesLeave::where('empId', $request->empId)->first();
            if ($leave) {
                $leave->el += 1;
                $leave->save();
                return ['status' => true];
            }
            return ['status' => false, 'message' => 'Leave record not found'];
        }
        return ['status' => false, 'message' => 'empId missing'];
    }

    public function decCL(Request $request)
    {
        if (!empty($request->empId)) {
            $leave = EmployeesLeave::where('empId', $request->empId)->first();
            if ($leave) {
                $leave->el -= 1;
                $leave->save();
                return ['status' => true];
            }
            return ['status' => false, 'message' => 'Leave record not found'];
        }
        return ['status' => false, 'message' => 'empId missing'];
    }


    public function addTotal(Request $request)
    {



        // $currentMonth = date('m');
        // $total = DB::table('leaves')->orderBy("created_at")->whereMonth('created_at', Carbon::now()->month)->get();
        // foreach($total as $total){
        //     $totalLeaveDays+=$total;
        // }



        $totalLeaveDays = '3';

        if ($totalLeaveDays != '') {
            $leave = EmployeesLeave::find($request->empId);
            $leave->taken = $totalLeaveDays;
            $leave->save();
            return ['status' => true];

        } else {
            return ['status' => false];
        }


    }
    public function deleteNotification($id)
    {
        $notification = auth()->user()->notifications()->find($id);
        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Notification deleted.');
        }
        return back()->with('error', 'Notification not found.');
    }

    // Delete all unread notifications
    public function deleteNotificationAll()
    {
        auth()->user()->unreadNotifications->each->delete();
        return back()->with('success', 'All unread notifications deleted.');
    }




}
