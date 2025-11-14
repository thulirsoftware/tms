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
use Illuminate\Http\Request;
use View;
use Response;

class SnapchatController extends Controller
{
    public function showReport(Employee $employee)
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


            if (Auth::user()->type == 'admin') {
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
        $tasks = Task::orderBy('startTime', 'ASC');

        if ($request->project != null) {
            $tasks = $tasks->where('projectId', $request->project);
        }

        if (Auth::user()->type == 'admin') {
            if ($request->employee != null) {
                $tasks = $tasks->where('empId', $request->employee);
            }
        } else {
            $tasks = $tasks->where('empId', Auth::user()->empId); // Fix: employee variable missing
        }

        if ($request->activity != null) {
            $tasks = $tasks->where('activityId', $request->activity);
        }

        if ($request->fromDate != null && $request->toDate == null) {
            $tasks = $tasks->where('takenDate', '=', $request->fromDate);
        }

        if ($request->toDate != null && $request->fromDate == null) {
            $tasks = $tasks->where('takenDate', '=', $request->toDate);
        }

        if ($request->fromDate != null && $request->toDate != null) {
            $tasks = $tasks->whereBetween('takenDate', [$request->fromDate, $request->toDate]);
        }

        $tasks = $tasks->groupBy('projectId')->groupBy('empId')->groupBy('activityId')->groupBy('takenDate')->orderBy('takenDate', 'Desc')->get();

        foreach ($tasks as $key => $task) {
            if (Task::where('relatedTaskId', $task->relatedTaskId)->where('status', 4)->count() > 0) {
                $tasks[$key]['flag'] = "Finished";
            }
        }

        $task = View::make('reports.partials.reportfilter', compact('employees', 'designations', 'projects', 'activities', 'taskStatus', 'priorities', 'tasks'))->render();

        return Response::json(['task' => $task]);
    }

}
