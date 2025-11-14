<?php

namespace App\Http\Controllers;
use DateTime;
use URL;
use Auth;
use Session;
use App\Task;
use App\InternTask;
use App\RegularTask;
use App\User;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use Illuminate\Http\Request;
use App\Notifications\TaskReminder;
use DB;
use Carbon\Carbon;

class TaskController extends Controller
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
        // Fetch all active employees excluding interns
        $employees = Employee::whereNull('empStatus')
            ->whereHas('user', function ($query) {
                $query->where('type', 'employee');
            })
            ->get();

        // Fetch all current tasks for employees
        $employeeTasks = Task::getAllCurrentTasks();

        // Fetch all active interns
        $interns = User::where('type', 'intern')
            ->whereHas('employee', function ($query) {
                $query->whereNull('empStatus');
            })
            ->get();

        // Fetch all current tasks for interns
        $internTasks = InternTask::getAllCurrentTasks();

        // ✅ Calculate total working time (hours, minutes, seconds) for today
        $today = Carbon::today()->toDateString();

        $employeeHours = Task::whereDate('takenDate', $today)
            ->whereNotNull('endTime')
            ->select(
                'empId',
                DB::raw('SUM(TIMESTAMPDIFF(SECOND, CONCAT(takenDate, " ", startTime), CONCAT(takenDate, " ", endTime))) as total_seconds')
            )
            ->groupBy('empId')
            ->pluck('total_seconds', 'empId')
            ->map(function ($seconds) {
                $hours = floor($seconds / 3600);
                $minutes = floor(($seconds % 3600) / 60);
                $secs = $seconds % 60;
                return sprintf('%02dh %02dm %02ds', $hours, $minutes, $secs);
            });

        return view('task.index', [
            'employees' => $employees,
            'employeeTasks' => $employeeTasks,
            'interns' => $interns,
            'internTasks' => $internTasks,
            'employeeHours' => $employeeHours,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($employee = '')
    {
        // Fetch the required data
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities($employee);
        $employees = Employee::getEmployeeList();
        $priorities = Task::getTaskPriorities();
        $task = new Task;

        // Pass the data to the view
        return view('task.create', compact('task', 'projects', 'activities', 'priorities', 'employees', 'employee'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Task $task)
    {
        // Assuming this is inside a controller method and $request is an instance of Illuminate\Http\Request
        $employee = Employee::find($request->empId);


        if ($employee) {
            // Find the user associated with the employee
            $user = User::where('empId', $employee->empId)->first();

            if ($user) {
                // Check if the user is an intern
                $isIntern = $user->type == 'intern';
            } else {
                // Handle the case where the user is not found
            }
        } else {
            // Handle the case where the employee is not found
        }

        // Use the appropriate model based on the user type
        $model = $isIntern ? InternTask::class : Task::class;

        if (!$task->exists) {
            $task = $model::create($request->all());
            $task->relatedTaskId = $task->id;
            $task->approval = 'yes';
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

        session()->flash('success', 'Employee Added Successfully');
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

    /**
     * Display the specified resource.
     *
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        $this->autoMarkPendingTasks();
        $taskTypes = RegularTask::getTaskTypes();
        $priorities = Task::getTaskPriorities();
        $taskStatus = CfgTaskStatus::getStatusList();

        if (!$employee->exists) {
            $employee = Auth::user()->employee;
        }

        // Check if the user is an intern
        $isIntern = Auth::user()->type === 'intern';

        if ($isIntern) {
            // Fetch tasks for intern
            $regularTasks = []; // No regular tasks for interns
            $assignedTasks = InternTask::getAssignedTasks($employee->id);
            $currentTasks = InternTask::getCurrentTasks($employee->id);
            $completedTasks = InternTask::getCompletedTasks($employee->id);
        } else {
            // Fetch tasks for employee
            $regularTasks = RegularTask::getRegularTasks($employee);
            $assignedTasks = Task::getAssignedTasks($employee->id);
            $currentTasks = Task::getCurrentTasks($employee->id);
            $completedTasks = Task::getCompletedTasks($employee->id);
        }

        $completedTasksRelatedId = $completedTasks->where('activityId', "!=", 1)
            ->where('activityId', "!=", 3)
            ->toArray();

        $completedTaskCount = count($completedTasksRelatedId);

        if ($completedTaskCount > 0) {
            foreach ($completedTasksRelatedId as $key => $value) {
                $selectedArray[$value['relatedTaskId']] = $value;
            }

            foreach ($selectedArray as $key => $value) {
                if ($isIntern) {
                    $minutes[$key] = DB::table('intern_tasks')->where('relatedTaskId', $key)
                        ->whereIn('status', [3, 4])
                        ->where('takenDate', date('Y-m-d'))
                        ->sum('minutes');

                    $hours[$key] = DB::table('intern_tasks')->where('relatedTaskId', $key)
                        ->whereIn('status', [3, 4])
                        ->where('takenDate', date('Y-m-d'))
                        ->sum('hours');
                } else {
                    $minutes[$key] = DB::table('tasks')->where('relatedTaskId', $key)
                        ->whereIn('status', [3, 4])
                        ->where('takenDate', date('Y-m-d'))
                        ->sum('minutes');

                    $hours[$key] = DB::table('tasks')->where('relatedTaskId', $key)
                        ->whereIn('status', [3, 4])
                        ->where('takenDate', date('Y-m-d'))
                        ->sum('hours');
                }

                $todayTotalMinutes[$key] = floor($minutes[$key] % 60);
                $todayTotalHours[$key] = $hours[$key] + floor($minutes[$key] / 60);
            }

            foreach ($selectedArray as $key => $value) {
                if ($isIntern) {
                    $uniqueTasks[$key] = InternTask::where('relatedTaskId', $key)
                        ->orderBy('id', 'DESC')
                        ->first()
                        ->toArray();
                } else {
                    $uniqueTasks[$key] = Task::where('relatedTaskId', $key)
                        ->orderBy('id', 'DESC')
                        ->first()
                        ->toArray();
                }
                $uniqueTasks[$key]['todayTotalHours'] = $todayTotalHours[$key];
                $uniqueTasks[$key]['todayTotalMinutes'] = $todayTotalMinutes[$key];

                $uniqueTaskStatus = DB::table('cfg_task_statuses')->where('id', $value['status'])->get()->toArray();
                $uniqueTaskActivity = DB::table('cfg_activities')->where('id', $value['activityId'])->get()->toArray();
                $uniqueTaskProperty = DB::table('projects')->where('id', $value['projectId'])->get()->toArray();

                $uniqueTasks[$key]['taskStatus'] = $uniqueTaskStatus[0]->name;
                $uniqueTasks[$key]['taskActivity'] = $uniqueTaskActivity[0]->name;
                $uniqueTasks[$key]['projectName'] = $uniqueTaskProperty[0]->projectName;
                $uniqueTasks[$key]['totalChartMinutes'] = ($uniqueTasks[$key]['todayTotalHours'] * 60) + $uniqueTasks[$key]['todayTotalMinutes'];
            }
        } else {
            $uniqueTasks = 0;
        }

        $chartList[] = 0;
        if ($uniqueTasks != 0) {
            $chartList[0] = ['Task', 'Number'];

            $i = 0;
            foreach ($uniqueTasks as $key => $value) {
                $activity = CfgActivity::where('id', $value['activityId'])->first();
                $chartList[++$i] = [$value['projectName'] . " (" . $activity['name'] . ")", $value['totalChartMinutes']];
            }
        }

        $projects = Project::getProjects();

        return view('task.view', compact('employee', 'assignedTasks', 'currentTasks', 'completedTasks', 'priorities', 'regularTasks', 'taskTypes', 'taskStatus', 'uniqueTasks', 'projects'))
            ->with('gender', json_encode($chartList));
    }

    public function adminShow(Employee $employee)
    {
        $this->autoMarkPendingTasks();
        $taskTypes = RegularTask::getTaskTypes();
        $priorities = Task::getTaskPriorities();

        // Handle case when employee is not provided
        if (!$employee->exists) {
            $employee = Auth::user()->employee;
        }

        // Determine if the employee is an intern
        $isIntern = $employee->user->type === 'intern';

        // Use the appropriate model based on the employee's type
        $taskModel = $isIntern ? InternTask::class : Task::class;

        // Retrieve tasks based on the employee type
        $regularTasks = RegularTask::getRegularTasksForAdmin($employee);
        $assignedTasks = $taskModel::getAssignedTasks($employee->id);
        $currentTasks = $taskModel::getCurrentTasks($employee->id);
        $completedTasks = $taskModel::getCompletedTasks($employee->id);

        $taskFilterData = Session::get('task.filter');
        $taskStatus = CfgTaskStatus::getStatusList();

        // Fetch completed tasks related IDs and handle them
        $completedTasksRelatedId = $taskModel::getCompletedTasks($employee->id)
            ->where('activityId', "!=", 1)
            ->where('activityId', "!=", 3)
            ->toArray();

        $completedTaskCount = count($completedTasksRelatedId);
        $uniqueTasks = [];

        if ($completedTaskCount > 0) {
            $selectedArray = [];
            foreach ($completedTasksRelatedId as $key => $value) {
                $selectedArray[$value['relatedTaskId']] = $value;
            }

            $todayTotalMinutes = [];
            $todayTotalHours = [];

            foreach ($selectedArray as $key => $value) {
                $minutes = DB::table('tasks')
                    ->where('relatedTaskId', $key)
                    ->whereIn('status', [3, 4])
                    ->where('takenDate', date('Y-m-d'))
                    ->sum('minutes');

                $hours = DB::table('tasks')
                    ->where('relatedTaskId', $key)
                    ->whereIn('status', [3, 4])
                    ->where('takenDate', date('Y-m-d'))
                    ->sum('hours');

                $todayTotalMinutes[$key] = floor($minutes % 60);
                $todayTotalHours[$key] = $hours + floor($minutes / 60);
            }

            foreach ($selectedArray as $key => $value) {
                $uniqueTask = $taskModel::where('relatedTaskId', $key)
                    ->orderBy('id', 'DESC')
                    ->first()
                    ->toArray();

                $uniqueTask['todayTotalHours'] = $todayTotalHours[$key];
                $uniqueTask['todayTotalMinutes'] = $todayTotalMinutes[$key];

                $uniqueTaskStatus = DB::table('cfg_task_statuses')
                    ->where('id', $value['status'])
                    ->first();
                $uniqueTaskActivity = DB::table('cfg_activities')
                    ->where('id', $value['activityId'])
                    ->first();
                $uniqueTaskProperty = DB::table('projects')
                    ->where('id', $value['projectId'])
                    ->first();

                $uniqueTask['taskStatus'] = $uniqueTaskStatus->name;
                $uniqueTask['taskActivity'] = $uniqueTaskActivity->name;
                $uniqueTask['projectName'] = $uniqueTaskProperty->projectName;
                $uniqueTask['totalChartMinutes'] = ($uniqueTask['todayTotalHours'] * 60) + $uniqueTask['todayTotalMinutes'];

                $uniqueTasks[$key] = $uniqueTask;
            }
        }

        $chartList = [['Task', 'Number']];
        if (!empty($uniqueTasks)) {
            foreach ($uniqueTasks as $value) {
                $activity = CfgActivity::find($value['activityId']);
                $chartList[] = [
                    $value['projectName'] . " (" . $activity->name . ")",
                    $value['totalChartMinutes']
                ];
            }
        }

        return view('task.adminView', compact(
            'employee',
            'assignedTasks',
            'currentTasks',
            'completedTasks',
            'priorities',
            'taskTypes',
            'regularTasks',
            'uniqueTasks',
            'taskStatus'
        ))->with('gender', json_encode($chartList));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task, $employee = '')
    {
        if (Task::find($task->id)) {

            $projects = Project::getProjects();
            $activities = CfgActivity::getActivities($task->empId);
            $employees = Employee::getEmployeeList();
            $priorities = Task::getTaskPriorities();
            return view('task.edit', compact('task', 'projects', 'activities', 'employees', 'employee', 'priorities'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
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
    public function destroy(Task $task)
    {
        if ($task->exists) {
            $employee = Employee::find($task->empId);
            $relatedTask = Task::where('relatedTaskId', $task->id)->delete();
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

    public function teamShow()
    {
        // Fetch all active employees excluding interns
        $employees = Employee::where('empStatus', null)
            ->whereHas('user', function ($query) {
                $query->where('type', 'employee');
            })
            ->get();

        // Fetch all current tasks for employees
        $employeeTasks = Task::getAllCurrentTasks();

        // Fetch all active interns
        $interns = User::where('type', 'intern')->whereHas('employee', function ($query) {
            $query->where('empStatus', null);
        })->get();

        // Fetch all current tasks for interns
        $internTasks = InternTask::getAllCurrentTasks();

        // Pass employees, interns, and current tasks to the view
        return view('task.index', [
            'employees' => $employees,
            'employeeTasks' => $employeeTasks,
            'interns' => $interns,
            'internTasks' => $internTasks,
        ]);
    }

    public function teamTaskShow(Employee $employee)
    {
        $this->autoMarkPendingTasks();
        $taskTypes = RegularTask::getTaskTypes();
        $priorities = Task::getTaskPriorities();

        // Handle case when employee is not provided
        if (!$employee->exists) {
            $employee = Auth::user()->employee;
        }

        // Determine if the employee is an intern
        $isIntern = $employee->user->type === 'intern';

        // Use the appropriate model based on the employee's type
        $taskModel = $isIntern ? InternTask::class : Task::class;

        // Retrieve tasks based on the employee type
        $regularTasks = RegularTask::getRegularTasksForAdmin($employee);
        $assignedTasks = $taskModel::getAssignedTasks($employee->id);
        $currentTasks = $taskModel::getCurrentTasks($employee->id);
        $completedTasks = $taskModel::getCompletedTasks($employee->id);

        $taskFilterData = Session::get('task.filter');
        $taskStatus = CfgTaskStatus::getStatusList();

        // Fetch completed tasks related IDs and handle them
        $completedTasksRelatedId = $taskModel::getCompletedTasks($employee->id)
            ->where('activityId', "!=", 1)
            ->where('activityId', "!=", 3)
            ->toArray();

        $completedTaskCount = count($completedTasksRelatedId);
        $uniqueTasks = [];

        if ($completedTaskCount > 0) {
            $selectedArray = [];
            foreach ($completedTasksRelatedId as $key => $value) {
                $selectedArray[$value['relatedTaskId']] = $value;
            }

            $todayTotalMinutes = [];
            $todayTotalHours = [];

            foreach ($selectedArray as $key => $value) {
                $minutes = DB::table('tasks')
                    ->where('relatedTaskId', $key)
                    ->whereIn('status', [3, 4])
                    ->where('takenDate', date('Y-m-d'))
                    ->sum('minutes');

                $hours = DB::table('tasks')
                    ->where('relatedTaskId', $key)
                    ->whereIn('status', [3, 4])
                    ->where('takenDate', date('Y-m-d'))
                    ->sum('hours');

                $todayTotalMinutes[$key] = floor($minutes % 60);
                $todayTotalHours[$key] = $hours + floor($minutes / 60);
            }

            foreach ($selectedArray as $key => $value) {
                $uniqueTask = $taskModel::where('relatedTaskId', $key)
                    ->orderBy('id', 'DESC')
                    ->first()
                    ->toArray();

                $uniqueTask['todayTotalHours'] = $todayTotalHours[$key];
                $uniqueTask['todayTotalMinutes'] = $todayTotalMinutes[$key];

                $uniqueTaskStatus = DB::table('cfg_task_statuses')
                    ->where('id', $value['status'])
                    ->first();
                $uniqueTaskActivity = DB::table('cfg_activities')
                    ->where('id', $value['activityId'])
                    ->first();
                $uniqueTaskProperty = DB::table('projects')
                    ->where('id', $value['projectId'])
                    ->first();

                $uniqueTask['taskStatus'] = $uniqueTaskStatus->name;
                $uniqueTask['taskActivity'] = $uniqueTaskActivity->name;
                $uniqueTask['projectName'] = $uniqueTaskProperty->projectName;
                $uniqueTask['totalChartMinutes'] = ($uniqueTask['todayTotalHours'] * 60) + $uniqueTask['todayTotalMinutes'];

                $uniqueTasks[$key] = $uniqueTask;
            }
        }

        $chartList = [['Task', 'Number']];
        if (!empty($uniqueTasks)) {
            foreach ($uniqueTasks as $value) {
                $activity = CfgActivity::find($value['activityId']);
                $chartList[] = [
                    $value['projectName'] . " (" . $activity->name . ")",
                    $value['totalChartMinutes']
                ];
            }
        }

        return view('task.adminView', compact(
            'employee',
            'assignedTasks',
            'currentTasks',
            'completedTasks',
            'priorities',
            'taskTypes',
            'regularTasks',
            'uniqueTasks',
            'taskStatus'
        ))->with('gender', json_encode($chartList));
    }
    public function autoMarkPendingTasks()
    {
        $currentTime = now()->format('H:i');
        $limitTime = '23:55'; // 11:55 PM

        if ($currentTime >= $limitTime) {
            // Fetch all current tasks that are still running past 11:55 PM
            $tasks = Task::where('status', 2) // Assuming 2 = 'In Progress'
                ->whereDate('takenDate', date('Y-m-d'))
                ->get();

            foreach ($tasks as $task) {
                $task->status = 3; // Assuming 5 = 'Pending' or your defined pending status
                $task->save();
            }

            // Do the same for intern tasks if needed
            $internTasks = InternTask::where('status', 2)
                ->whereDate('takenDate', date('Y-m-d'))
                ->get();

            foreach ($internTasks as $task) {
                $task->status = 3;
                $task->save();
            }
        }
    }


}

