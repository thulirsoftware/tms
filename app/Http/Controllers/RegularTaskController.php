<?php

namespace App\Http\Controllers;
use DateTime;
use URL;
use Auth;
use Session;
use App\Task;
use App\RegularTask;
use App\User;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use Illuminate\Http\Request;
use App\Notifications\TaskReminder;

class RegularTaskController extends Controller
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
        $currentTasks = Task::getAllCurrentTasks();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        return view('task.index', compact('employees', 'designations', 'currentTasks', 'projects', 'activities'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employee = '')
    {

        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities($employee);
        $employees = Employee::getEmployeeList();
        $priorities = Task::getTaskPriorities();
        $taskTypes = RegularTask::getTaskTypes();
        $designation = CfgDesignations::getDesignation();
        $task = new Task;
        return view('regularTask.create', compact('designation', 'task', 'projects', 'activities', 'priorities', 'taskTypes', 'employees', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RegularTask $task)
    {
        if (!$task->exists) {
            $task = RegularTask::create($request->all());
            $task->save();
        } else {
            if ($task->endTime != null) {
                $task->update($request->all());
            }
        }
        if (Auth::user()->type == 'admin') {
            $employee = Employee::find($task->empId);
            $user = User::where('empId', $employee->empId)->first();
            $user->notify(new TaskReminder($task, $employee, 'assign a task'));
        } else {
            $employee = Employee::find($task->empId);
            if ($employee->empId != Auth::user()->empId) {
                $user = User::where('empId', $employee->empId)->first();
                $user->notify(new TaskReminder($task, $employee, 'assign a task'));
            }
            $user = User::where('empId', 'admin')->first();
            $user->notify(new TaskReminder($task, $employee, 'assign a task'));
        }


        session()->flash('success', 'Employee Added Succesfully');
        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks')) {

            if ($employee->email != Auth::user()->email) {
                // Assigned to someone else
                return redirect('/Admin/Task/' . $request->empId);
            } else {
                // Assigned to themselves
                return redirect('/Task');
            }
        }
        return redirect('/Task');
    }

    //     /**
//      * Display the specified resource.
//      *
//      * @param  \App\task  $task
//      * @return \Illuminate\Http\Response
//      */
//     public function show(Employee $employee)
//     {

    //         $designations=CfgDesignations::getDesignation();
//         $projects=Project::getProjects();
//         $activities=CfgActivity::getActivities();
//         $taskStatus=CfgTaskStatus::getStatusList();
//         $priorities=Task::getTaskPriorities();
//         if(!$employee->exists){    

    //             $employee=Employee::where('empId',Auth::user()->empId)->first();
//         }      
//         $assignedTasks=Task::getAssignedTasks($employee->id);
//         $currentTasks=Task::getCurrentTasks($employee->id);
//         $completedTasks=Task::getCompletedTasks($employee->id);

    //         $taskFilterData=Session::get('task.filter');

    // if($taskFilterData!=null){
//         $tasks = new Task;

    //         if ($taskFilterData['project']!=null) {
//             $tasks=Task::Where('projectId',$taskFilterData['project']);
//         }        

    //         if ($taskFilterData['activity']!=null) {
//             $tasks = $tasks->Where('activityId',$taskFilterData['activity']);
//         }  

    //         if (Auth::user()->type=='admin') {
//             if($taskFilterData['employee']!=null){
//                 $tasks = $tasks->Where('empId',$taskFilterData['employee']);
//             } 
//         }else{
//             $tasks = $tasks->Where('empId',$employee->id);
//         } 

    //         if ($taskFilterData['assignedDate']!=null) {
//             $tasks = $tasks->Where('assignedDate',$taskFilterData['assignedDate']);
//         }    

    //         if ($taskFilterData['takenDate']!=null) {
//             $tasks = $tasks->Where('takenDate',$taskFilterData['takenDate']);
//         }          

    //         if ($taskFilterData['status']!=null) {
//             $tasks = $tasks->Where('status',$taskFilterData['status']);
//         foreach ($tasks as $key => $task) {
//            if($task->status==3)
//            {
//             if(Task::where('relatedTaskId',$task->relatedTaskId)->whereIn('status',array(2,4))->count()>0)
//             {
//                 $tasks->pull($key); 
//             }
//            }
//         }
//         }       

    //         $tasks = $tasks->orderBy('id','desc')->get();


    // }

    //         return view('task.view',compact('employee','designations','projects','activities','taskStatus','assignedTasks','currentTasks','completedTasks','priorities','tasks'));

    //     }

    public function adminShow(Employee $employee)
    {

        $designations = CfgDesignations::getDesignation();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        $taskStatus = CfgTaskStatus::getStatusList();
        $priorities = Task::getTaskPriorities();
        if (!$employee->exists) {

            $employee = Employee::where('empId', Auth::user()->empId)->first();
        }
        $assignedTasks = Task::getAssignedTasks($employee->id);
        $currentTasks = Task::getCurrentTasks($employee->id);
        $completedTasks = Task::getCompletedTasks($employee->id);

        $taskFilterData = Session::get('task.filter');


        return view('task.adminView', compact('employee', 'designations', 'projects', 'activities', 'taskStatus', 'assignedTasks', 'currentTasks', 'completedTasks', 'priorities'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(RegularTask $task, $employee = '')
    {
        if (RegularTask::find($task->id)) {

            $projects = Project::getProjects();
            $activities = CfgActivity::getActivities($task->empId);
            $employees = Employee::getEmployeeList();
            $priorities = Task::getTaskPriorities();
            $taskTypes = RegularTask::getTaskTypes();
            return view('regularTask.edit', compact('task', 'projects', 'activities', 'employees', 'employee', 'priorities', 'taskTypes'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RegularTask $task)
    {
        // dd($request->all(),$task);

        if ($task->exists) {
            $employee = Employee::find($task->empId);
            $task->update($request->all());
            if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks')) {

                if ($employee->email != Auth::user()->email) {
                    // Assigned to someone else
                    return redirect('/Admin/Task/' . $request->empId);
                } else {
                    // Assigned to themselves
                    return redirect('/Task');
                }
            }
            return redirect('/Task');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(RegularTask $task)
    {
        if ($task->exists) {
            $employee = Employee::find($task->empId);
            $task->delete();
            if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks')) {

                if ($employee->email != Auth::user()->email) {
                    // Assigned to someone else
                    return redirect('/Admin/Task/' . $task->empId);
                } else {
                    // Assigned to themselves
                    return redirect('/Task');
                }
            }
            return redirect('/Task');
        }
    }

}
