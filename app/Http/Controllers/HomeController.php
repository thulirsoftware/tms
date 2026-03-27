<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Task;
use App\Employee;
use App\Project;
use Carbon\Carbon;
class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function myHome()
    {
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');
    
        $projectIds = Project::orderby('id', 'desc')->pluck('id');
    
        $taskInfos = collect();
    
        if (Auth::user()) {
            if (Auth::user()->type == 'admin') {
                return redirect('/Admin');
            }
            
            $employee = Employee::where('email', Auth::user()->email)->first();
            
            if ($employee) {
                $taskInfos = Task::whereBetween('takenDate', [$weekStartDate, $weekEndDate])
                    ->where('empId', $employee->id)
                    ->whereIn('projectId', $projectIds)
                    ->groupBy('projectId')
                    ->orderBy('id', 'desc')
                    ->get();
            }
        }
    
        return view('myHome', compact('taskInfos', 'weekStartDate', 'weekEndDate'));
    }
    
   /* public function adminHome()
    {
       $project = Project::orderby('id','desc')->pluck('id')->take(17);
        $taskInfos = Task::orderby('projectId','desc')->whereIn('projectId',$project)->groupBy('empId')->groupBy('projectId')->get();

        return view('admin.adminHome',compact('taskInfos'));
    }*/
    
    public function adminHome(Request $request)
    {
        $now = Carbon::now();
        $weekStartDate = (clone $now)->startOfWeek()->format('Y-m-d');
        $currentDate = $now->toDateString();
    
        $startDate = $request->input('start_date', $weekStartDate);
        $endDate = $request->input('end_date', $currentDate);
        $employeeId = $request->input('employee_id');
        $excludedProjectId = 72; // ID to be excluded
    
        // Get the latest projects except the one with ID 34
        $projectIds = Project::orderby('id', 'desc')
            ->where('id', '!=', $excludedProjectId)
            ->pluck('id');
    
        // Get task info for the selected projects within the specified date range and optionally for a specific employee
        $taskInfosQuery = Task::orderby('projectId', 'desc')
            ->whereIn('projectId', $projectIds)
            ->whereBetween('assignedDate', [$startDate, $endDate]);
    
        if ($employeeId) {
            $taskInfosQuery->where('empId', $employeeId);
        }
    
        $taskInfos = $taskInfosQuery->groupBy('empId')
            ->groupBy('projectId')
            ->get();
    
        // Initialize the arrays for employee names, project names, and total hours
        $employeeName = [];
        $projectName = [];
        $totalHours = [];
    
        // Prepare the chart data and task table data
        $chartList = [['Employee', 'Project', 'Number']];
        $calculatedTaskInfos = [];
    
        foreach ($taskInfos as $value) {
            $project = Project::find($value->projectId);
            $employee = Employee::find($value->empId);
    
            if ($project && $employee) {
                $hours = Task::where('projectId', $value->projectId)
                    ->where('empId', $value->empId)
                    ->whereBetween('assignedDate', [$startDate, $endDate])
                    ->sum('hours');
                $minutes = Task::where('projectId', $value->projectId)
                    ->where('empId', $value->empId)
                    ->whereBetween('assignedDate', [$startDate, $endDate])
                    ->sum('minutes');
    
                $totalMinutes = $minutes % 60;
                $totalHoursValue = $hours + floor($minutes / 60);
    
                // Ensure the arrays are indexed correctly
                if (!isset($employeeName[$employee->id])) {
                    $employeeName[$employee->id] = $employee->name;
                }
                $projectName[$employee->id][] = $project->projectName;
                $totalHours[$employee->id][] = $totalHoursValue;
    
                $chartList[] = [$employee->name, $project->projectName, $totalHoursValue];
    
                $activity = $value->activity;
    
                $calculatedTaskInfos[] = [
                    'project' => $project,
                    'employee' => $employee,
                    'activity' => $activity,
                    'totalHours' => $totalHoursValue,
                    'totalMinutes' => $totalMinutes
                ];
            }
        }
    
        // Sort the tasks by totalHours in descending order, and by totalMinutes in descending order in case of a tie
        usort($calculatedTaskInfos, function ($a, $b) {
            // Compare by totalHours first
            if ($a['totalHours'] === $b['totalHours']) {
                // If totalHours are the same, compare by totalMinutes
                return $b['totalMinutes'] <=> $a['totalMinutes'];
            }
            return $b['totalHours'] <=> $a['totalHours'];
        });
    
        $employees = Employee::orderBy('name', 'asc')->get();
    
        return view('admin.adminHome', compact('calculatedTaskInfos', 'chartList', 'employeeName', 'projectName', 'totalHours', 'employees', 'startDate', 'endDate', 'employeeId'));
    }
    
    
    /**
     * Show the my users page.
     *
     * @return \Illuminate\Http\Response
     */
    public function myUsers()
    {
        return view('myUsers');
    }
}
