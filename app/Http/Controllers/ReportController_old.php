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
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Employee $employee)
    {
        
        $designations=CfgDesignations::getDesignation();
        $projects=Project::getProjects();
        $activities=CfgActivity::getActivities();
        $taskStatus=CfgTaskStatus::getStatusList();
        $priorities=Task::getTaskPriorities();
        $employees=Employee::getEmployeeList();
        if(!$employee->exists){    
            $employee=Employee::where('empId',Auth::user()->empId)->first();
        }   
        $taskFilterData=Session::get('task.filter');
        $tasks = new Task;
        if ($taskFilterData['project']!=null) {
            $tasks=Task::Where('projectId',$taskFilterData['project']);
        }        
        
        if ($taskFilterData['activity']!=null) {
            $tasks = $tasks->Where('activityId',$taskFilterData['activity']);
        }  
        if (Auth::user()->type=='admin') {
            if($taskFilterData['employee']!=null){
                $tasks = $tasks->Where('empId',$taskFilterData['employee']);
            } 
        }else{
            $tasks = $tasks->Where('empId',$employee->id);
        } 
        
        if ($taskFilterData['assignedDate']!=null) {
            $tasks = $tasks->Where('assignedDate',$taskFilterData['assignedDate']);
  
        }    
        if ($taskFilterData['takenDate']!=null) {
            $tasks = $tasks->Where('takenDate',$taskFilterData['takenDate']);
        
        }  
        if ($taskFilterData['fromDate']!=null) {
            $tasks = $tasks->Where('takenDate','>=',$taskFilterData['fromDate']);
        
        }  
        if ($taskFilterData['toDate']!=null) {
            $tasks = $tasks->Where('takenDate','<=',$taskFilterData['toDate']);
        
        }          
        if ($taskFilterData['status']!=null) {
            $tasks = $tasks->Where('status',$taskFilterData['status']);
        }       
        $tasks = $tasks->orderBy('startTime','ASC')->take(300)->get();
        foreach ($tasks as $key => $task) {
            if(Task::where('relatedTaskId',$task->relatedTaskId)->where('status',4)->count()>0)
            {
                $tasks[$key]['flag']="Finished"; 
                
            }
        }
      
        return view('report.taskReport',compact('employees','designations','projects','activities','taskStatus','priorities','tasks'));
    }
 public function email()
    {
        $tasks=Report::getTodayTask();
        $designations=CfgDesignations::getDesignation();
        $projects=Project::getProjects();
        $activities=CfgActivity::getActivities();
        $taskStatus=CfgTaskStatus::getStatusList();
        $priorities=Task::getTaskPriorities();
        $employees=Employee::getEmployeeList();
        if(!empty($tasks))
        {
          
            return view('report.emails.dailyTasks',['employees'=>$employees,'designations'=>$designations,'projects'=>$projects,'activities'=>$activities,'taskStatus'=>$taskStatus,'priorities'=>$priorities,'tasks'=>$tasks]);
            $to='thulirsoft@gmail.com';
             Mail::send('report.emails.dailyTasks',['employees'=>$employees,'designations'=>$designations,'projects'=>$projects,'activities'=>$activities,'taskStatus'=>$taskStatus,'priorities'=>$priorities,'tasks'=>$tasks],function ($message) use($to) 
                {
                    $message->to($to)->subject('Daily Task Report - '.date('d-m-Y'));
                
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
}