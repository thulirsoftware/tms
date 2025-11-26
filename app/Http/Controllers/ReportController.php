<?php

namespace App\Http\Controllers;
use DateTime;
use URL;
use Auth;
use Mail;
use Session;
use App\Task;
use App\Report;
use App\Employee;
use App\Project;
use App\CfgActivity;
use App\CfgDesignations;
use App\CfgTaskStatus;
use App\InternTask;
use Illuminate\Http\Request;
use View;
use Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $employee, Request $request)
    {

        $designations = CfgDesignations::getDesignation();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        $taskStatus = CfgTaskStatus::getStatusList();
        $priorities = Task::getTaskPriorities();

        $employees = Employee::getEmployeeList();
        if (!$employee->exists) {

            $employee = Employee::where('empId', Auth::user()->empId)->first();
        }
        $isIntern = 0;
        if($request->has('employee') && $request->employee != null)
        {
            $employee = Employee::where('id', $request->employee)->first();
             $isIntern = $employee->user->type === 'intern';
        }

       
         $tasks = $isIntern ? InternTask::orderby('id', 'desc') : Task::orderby('id', 'desc');

         if ($request->project != null) {
             $tasks =  $isIntern ? InternTask::Where('projectId', $request->project) : Task::Where('projectId', $request->project);

 
        }

        if ($request->activity != null) {
            $tasks = $tasks->Where('activityId', $request->activity);

        }


        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Report')) {

            if ($request->employee != null) {

                $tasks = $tasks->Where('empId', $request->employee);

            }
        } else {
            $tasks = $tasks->Where('empId', $employee->id);
        }

        if ($request->assignedDate != null) {
            $tasks = $tasks->Where('assignedDate', $request->assignedDate);

        }

        if ($request->takenDate != null) {
            $tasks = $tasks->Where('takenDate', $request->takenDate);

        }
        // if ($request->takenDate == null && $request->fromDate == null && $request->toDate == null) {
        //     $tasks = $tasks->Where('takenDate', date('Y-m-d'));

        // }

        if ($request->fromDate != null) {
            $tasks = $tasks->Where('takenDate', '>=', $request->fromDate);

        }

        if ($request->toDate != null) {
            $tasks = $tasks->Where('takenDate', '<=', $request->toDate);

        }

        if ($request->status != null) {
            $tasks = $tasks->Where('status', $request->status);

        }

        $tasks = $tasks->orderBy('startTime', 'ASC')->get();
        foreach ($tasks as $key => $task) {
            if (Task::where('relatedTaskId', $task->relatedTaskId)->where('status', 4)->count() > 0) {
                $tasks[$key]['flag'] = "Finished";

            }
        }
        return view('report.taskReport', compact('employees', 'designations', 'projects', 'activities', 'taskStatus', 'priorities', 'tasks'));

    }
    public function employeeReport(Request $request)
    {
        $employees = Employee::all(); // For filter dropdown
        $designations = CfgDesignations::pluck('name', 'id'); // id => name mapping

        $employeeId = $request->employee;
        $fromDate = $request->fromDate ?: date('Y-m-d');
        $toDate = $request->toDate ?: date('Y-m-d');
        $period = $request->period ?? 'daily';

        $tasksQuery = Task::query();

        if ($employeeId) {
            $tasksQuery->where('empId', $employeeId);
        }

        $tasksQuery->whereBetween('takenDate', [$fromDate, $toDate]);

        $tasks = $tasksQuery->orderBy('takenDate', 'ASC')->get();

        // Group by employee + date
        $reportData = [];

        foreach ($tasks as $task) {
            $empKey = $task->empId;
            $dateKey = $task->takenDate;

            if (!isset($reportData[$empKey])) {
                $reportData[$empKey] = [];
            }

            if (!isset($reportData[$empKey][$dateKey])) {
                $reportData[$empKey][$dateKey] = [
                    'employeeName' => $task->employee->name ?? '-',
                    'designation' => $designations[$task->employee->designation] ?? '-', // fetch name
                    'workMinutes' => 0,
                ];
            }

            // Work time excludes Lunch (1) and Break (3)
            $hours = $task->hours ?? 0;
            $minutes = $task->minutes ?? 0;
            $totalMins = $hours * 60 + $minutes;

            if (!in_array($task->activityId, [1, 3])) {
                $reportData[$empKey][$dateKey]['workMinutes'] += $totalMins;
            }
        }

        // Format work hours, total hours is always 8:30
        foreach ($reportData as $empId => &$dates) {
            foreach ($dates as $date => &$data) {
                $data['totalHours'] = '08:30'; // standard total hours
                $data['workHours'] = str_pad(floor($data['workMinutes'] / 60), 2, "0", STR_PAD_LEFT) . ':' . str_pad($data['workMinutes'] % 60, 2, "0", STR_PAD_LEFT);
            }
        }

        return view('report.employeeBased', compact('employees', 'reportData', 'fromDate', 'toDate', 'employeeId', 'period'));
    }
    public function employeeReportAjax(Request $request)
    {
        $employees = Employee::all();
        $designations = CfgDesignations::pluck('name', 'id');

        $employeeId = $request->employee;
        $period = $request->period ?? 'daily';
        $fromDate = $request->fromDate;
        $toDate = $request->toDate;

        $tasksQuery = Task::with('employee')->orderBy('takenDate', 'ASC');

        if ($employeeId) {
            $tasksQuery->where('empId', $employeeId);
        }

        // Custom range overrides period
        if ($period === 'custom' && $fromDate && $toDate) {
            $tasksQuery->whereBetween('takenDate', [$fromDate, $toDate]);
        } else {
            $today = now()->format('Y-m-d');

            if ($period === 'daily') {
                $tasksQuery->whereDate('takenDate', $today);
            } elseif ($period === 'weekly') {
                $tasksQuery->whereBetween('takenDate', [now()->startOfWeek()->format('Y-m-d'), now()->endOfWeek()->format('Y-m-d')]);
            } elseif ($period === 'monthly') {
                $tasksQuery->whereBetween('takenDate', [now()->startOfMonth()->format('Y-m-d'), now()->endOfMonth()->format('Y-m-d')]);
            }
        }

        $tasks = $tasksQuery->get();

        $reportData = [];
        foreach ($tasks as $task) {
            $empKey = $task->empId;
            $dateKey = $task->takenDate;

            if (!isset($reportData[$empKey][$dateKey])) {
                $reportData[$empKey][$dateKey] = [
                    'employeeName' => $task->employee->name ?? '-',
                    'designation' => $designations[$task->employee->designation] ?? '-',
                    'workMinutes' => 0,
                ];
            }

            $hours = $task->hours ?? 0;
            $minutes = $task->minutes ?? 0;
            $totalMins = $hours * 60 + $minutes;

            if (!in_array($task->activityId, [1, 3])) {
                $reportData[$empKey][$dateKey]['workMinutes'] += $totalMins;
            }
        }

        foreach ($reportData as $empId => &$dates) {
            foreach ($dates as $date => &$data) {
                $data['totalHours'] = '08:30';
                $data['workHours'] = str_pad(floor($data['workMinutes'] / 60), 2, "0", STR_PAD_LEFT)
                    . ':' . str_pad($data['workMinutes'] % 60, 2, "0", STR_PAD_LEFT);
            }
        }

        return view('report.partials.employeeTable', compact('reportData'));
    }

    public function employeeReportDownload(Request $request)
    {
        $employeeId = $request->employee;
        $period = $request->period ?? 'daily';
        $today = now()->format('Y-m-d');

        $tasksQuery = Task::with('employee')->orderBy('takenDate', 'ASC');

        if ($employeeId) {
            $tasksQuery->where('empId', $employeeId);
        }

        // Filter by period
        if ($period == 'daily') {
            $tasksQuery->whereDate('takenDate', $today);
        } elseif ($period == 'weekly') {
            $tasksQuery->whereBetween('takenDate', [
                now()->startOfWeek()->format('Y-m-d'),
                now()->endOfWeek()->format('Y-m-d')
            ]);
        } elseif ($period == 'monthly') {
            $tasksQuery->whereBetween('takenDate', [
                now()->startOfMonth()->format('Y-m-d'),
                now()->endOfMonth()->format('Y-m-d')
            ]);
        }

        $tasks = $tasksQuery->get();
        $designations = CfgDesignations::pluck('name', 'id');

        // Prepare report data
        $reportData = [];
        foreach ($tasks as $task) {
            $empKey = $task->empId;
            $dateKey = $task->takenDate;

            if (!isset($reportData[$empKey]))
                $reportData[$empKey] = [];
            if (!isset($reportData[$empKey][$dateKey])) {
                $reportData[$empKey][$dateKey] = [
                    'employeeName' => $task->employee->name ?? '-',
                    'designation' => $designations[$task->employee->designation] ?? '-',
                    'workMinutes' => 0,
                ];
            }

            $totalMins = ($task->hours ?? 0) * 60 + ($task->minutes ?? 0);
            if (!in_array($task->activityId, [1, 3])) {
                $reportData[$empKey][$dateKey]['workMinutes'] += $totalMins;
            }
        }

        // Prepare CSV rows
        $csvData = [];
        $csvData[] = ['Date', 'Employee', 'Designation', 'Total Hours', 'Working Hours'];

        foreach ($reportData as $empId => $dates) {
            foreach ($dates as $date => $data) {
                $workHours = str_pad(floor($data['workMinutes'] / 60), 2, "0", STR_PAD_LEFT)
                    . ':' . str_pad($data['workMinutes'] % 60, 2, "0", STR_PAD_LEFT);
                $csvData[] = [
                    \Carbon\Carbon::parse($date)->format('Y-m-d'),
                    $data['employeeName'],
                    $data['designation'],
                    '08:30', // standard total hours
                    $workHours
                ];
            }
        }

        $filename = 'employee_report_' . now()->format('Ymd_His') . '.csv';

        // Stream CSV download with proper headers
        $handle = fopen('php://memory', 'w');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);

        return response()->streamDownload(function () use ($handle) {
            fpassthru($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }








    public function projectReport(Request $request)
    {
        $projects = Project::getProjects();

        // Base query
        $tasks = Task::query()
            ->with(['project', 'activity'])
            ->whereNotNull('takenDate');

        // Apply filters
        if ($request->project) {
            $tasks->where('projectId', $request->project);
        }

        if ($request->fromDate) {
            $tasks->whereDate('takenDate', '>=', $request->fromDate);
        }

        if ($request->toDate) {
            $tasks->whereDate('takenDate', '<=', $request->toDate);
        }

        // Get all filtered tasks
        $tasks = $tasks->get();

        // Group by date + project + activity
        $grouped = $tasks->groupBy(function ($item) {
            return $item->takenDate . '-' . $item->projectId . '-' . $item->activityId;
        });

        $reportData = [];
        $grandTotalMinutes = 0;

        foreach ($grouped as $group) {
            $first = $group->first();
            $totalMinutes = 0;

            foreach ($group as $task) {
                $totalMinutes += ($task->hours * 60) + $task->minutes;
            }

            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;

            $grandTotalMinutes += $totalMinutes;

            $reportData[] = [
                'date' => date('Y-m-d', strtotime($first->takenDate)),
                'project' => $first->project->projectName ?? '-',
                'activity' => $first->activity->name ?? '-',
                'total_hours' => sprintf('%02d:%02d', $hours, $minutes),
            ];
        }

        // Calculate final total across all
        $grandHours = floor($grandTotalMinutes / 60);
        $grandMinutes = $grandTotalMinutes % 60;
        $grandTotal = sprintf('%02d:%02d', $grandHours, $grandMinutes);

        return view('report.projectBased', [
            'projects' => $projects,
            'reportData' => $reportData,
            'grandTotal' => $grandTotal,
            'request' => $request
        ]);
    }
    public function taskReport(Request $request)
    {
        $query = Task::query()
            ->join('users as assigner_user', 'assigner_user.id', '=', 'tasks.assignedBy')
            ->join('employees as assigner_emp', 'assigner_emp.empId', '=', 'assigner_user.empId')
            ->join('employees as assigned_emp', 'assigned_emp.id', '=', 'tasks.empId')
            ->whereColumn('assigner_emp.id', '!=', 'assigned_emp.id')
            ->select([
                'tasks.*',
                'assigner_user.id as assigned_by_user_id',
                'assigner_emp.id as assigned_by_emp_id',
                'assigner_emp.name as assigned_by_name',
                'assigned_emp.id as assigned_to_emp_id',
                'assigned_emp.name as assigned_to_name'
            ])
            ->orderBy('tasks.assignedDate', 'desc');

        // 🔎 Filter correctly using user.id for Assigned By
        if ($request->assignedBy) {
            $query->where('tasks.assignedBy', $request->assignedBy);
        }

        if ($request->employee) {
            $query->where('tasks.empId', $request->employee);
        }

        if ($request->from_date && $request->to_date) {
            $query->whereBetween('tasks.assignedDate', [$request->from_date, $request->to_date]);
        } elseif ($request->from_date) {
            $query->whereDate('tasks.assignedDate', '>=', $request->from_date);
        } elseif ($request->to_date) {
            $query->whereDate('tasks.assignedDate', '<=', $request->to_date);
        }

        $tasks = $query->get();

        // ✅ Use users.id for AssignedBy dropdown
        $assignedByList = $tasks->pluck('assigned_by_name', 'assigned_by_user_id')->unique();
        $employees = $tasks->pluck('assigned_to_name', 'assigned_to_emp_id')->unique();

        if ($request->ajax()) {
            return response()->json([
                'table' => view('report.partials.task_table', compact('tasks'))->render(),
                'assignedByList' => $assignedByList,
                'employees' => $employees,
            ]);
        }

        return view('report.task_report', compact('tasks', 'employees', 'assignedByList'));
    }











    public function email()
    {
        $tasks = Report::getTodayTask();
        $designations = CfgDesignations::getDesignation();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        $taskStatus = CfgTaskStatus::getStatusList();
        $priorities = Task::getTaskPriorities();
        $employees = Employee::getEmployeeList();
        if (!empty($tasks)) {

            return view('report.emails.dailyTasks', ['employees' => $employees, 'designations' => $designations, 'projects' => $projects, 'activities' => $activities, 'taskStatus' => $taskStatus, 'priorities' => $priorities, 'tasks' => $tasks]);
            $to = 'thulirsoft@gmail.com';

            Mail::send('report.emails.dailyTasks', ['employees' => $employees, 'designations' => $designations, 'projects' => $projects, 'activities' => $activities, 'taskStatus' => $taskStatus, 'priorities' => $priorities, 'tasks' => $tasks], function ($message) use ($to) {
                $message->to($to)->subject('Daily Task Report - ' . date('d-m-Y'));

            });
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
    public function ReportList(Employee $employee)
    {
        $designations = CfgDesignations::getDesignation();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        $taskStatus = CfgTaskStatus::getStatusList();
        $priorities = Task::getTaskPriorities();

        $employees = Employee::getEmployeeList();
        if (!$employee->exists) {

            $employee = Employee::where('empId', Auth::user()->empId)->first();
        }
        $taskFilterData = Session::get('task.filter');
        if ($taskFilterData == null) {
            $tasks = new Task;
            $tasks = $tasks->groupBy('projectId')->groupBy('empId')->groupBy('activityId')->groupBy('takenDate')->orderBy('takenDate', 'Desc')->take(150)->get();
            foreach ($tasks as $key => $task) {
                if (Task::where('relatedTaskId', $task->relatedTaskId)->where('status', 4)->count() > 0) {
                    $tasks[$key]['flag'] = "Finished";

                }
            }


            return view('reports.taskReport', compact('employees', 'designations', 'projects', 'activities', 'taskStatus', 'priorities', 'tasks'));
        } else {
            $taskFilterData = Session::get('task.filter');
            $tasks = new Task;
            if ($taskFilterData['project'] != null) {
                $tasks = Task::Where('projectId', $taskFilterData['project']);

            }


            if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Report')) {
                if ($taskFilterData['employee'] != null) {
                    $tasks = $tasks->Where('empId', $taskFilterData['employee']);
                }
            } else {
                $tasks = $tasks->Where('empId', $employee->id);
            }


            if ($taskFilterData['fromDate'] != null) {
                $tasks = $tasks->Where('takenDate', '=', $taskFilterData['fromDate']);

            }

            if ($taskFilterData['toDate'] != null) {
                $tasks = $tasks->Where('takenDate', '=', $taskFilterData['toDate']);

            }
            if ($taskFilterData['fromDate'] != null && $taskFilterData['toDate'] != null) {
                $tasks = $tasks->Where('takenDate', '>=', $taskFilterData['fromDate'])->Where('takenDate', '<=', $taskFilterData['toDate']);

            }


            $tasks = $tasks->groupBy('projectId')->groupBy('empId')->groupBy('activityId')->groupBy('takenDate')->orderBy('takenDate', 'Desc')->get();
            foreach ($tasks as $key => $task) {
                if (Task::where('relatedTaskId', $task->relatedTaskId)->where('status', 4)->count() > 0) {
                    $tasks[$key]['flag'] = "Finished";

                }
            }


            return view('reports.taskReport', compact('employees', 'designations', 'projects', 'activities', 'taskStatus', 'priorities', 'tasks'));
        }
    }

    public function ReportsFilter(Request $request)
    {
        $designations = CfgDesignations::getDesignation();
        $projects = Project::getProjects();
        $activities = CfgActivity::getActivities();
        $taskStatus = CfgTaskStatus::getStatusList();
        $priorities = Task::getTaskPriorities();

        $employees = Employee::getEmployeeList();
        $tasks = Task::orderBy('takenDate', 'Desc');

        // Filter by project
        if ($request->project != null) {
            $tasks = $tasks->where('projectId', $request['project']);
        }

        // Filter by employee (admin vs normal user)
        if (Auth::user()->type == 'admin' || Auth::user()->hasPermission('Report')) {
            if ($request['employee'] != null) {
                $tasks = $tasks->where('empId', $request['employee']);
            }
        } else {
            $tasks = $tasks->where('empId', Auth::user()->employee->id ?? null);
        }

        // Filter by activity
        if ($request['activity'] != null) {
            $tasks = $tasks->where('activityId', $request['activity']);
        }

        // Filter by fromDate / toDate
        if ($request['fromDate'] != null && $request['toDate'] == null) {
            $tasks = $tasks->where('takenDate', '>=', $request['fromDate']);
        }

        if ($request['toDate'] != null && $request['fromDate'] == null) {
            $tasks = $tasks->where('takenDate', '<=', $request['toDate']);
        }

        if ($request['fromDate'] != null && $request['toDate'] != null) {
            $tasks = $tasks->whereBetween('takenDate', [$request['fromDate'], $request['toDate']]);
        }

        // Group & order
        $tasks = $tasks->groupBy('projectId')
            ->groupBy('empId')
            ->groupBy('activityId')
            ->groupBy('takenDate')
            ->orderBy('takenDate', 'DESC')
            ->get();

        // Add flag for finished tasks
        foreach ($tasks as $key => $task) {
            if (Task::where('relatedTaskId', $task->relatedTaskId)->where('status', 4)->count() > 0) {
                $tasks[$key]['flag'] = "Finished";
            }
        }

        $task = View::make('reports.partials.reportfilter', compact(
            'employees',
            'designations',
            'projects',
            'activities',
            'taskStatus',
            'priorities',
            'tasks'
        ))->render();

        return Response::json(['task' => $task]);
    }

}
