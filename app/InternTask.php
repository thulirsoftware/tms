<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternTask extends Model
{
    use SoftDeletes;

    // Specify the table associated with the model
    protected $table = 'intern_tasks';

    // Define the fillable attributes
    protected $fillable = [
        'assignedDate', 'takenDate', 'assignedBy', 'relatedTaskId', 
        'empId', 'projectId', 'activityId', 'instruction', 
        'priority', 'comment', 'startTime', 'endTime', 'approval', 'status'
    ];

    // Define the attributes that should be hidden for arrays
    protected $hidden = [];

    // Define the attributes that are date instances
    protected $dates = ['deleted_at'];

    public static function getAssignedTasks($empId)
    {
        $assignedTask = InternTask::whereIn('status',array(1,3))->where('empId',$empId)->whereRaw('id = relatedTaskId')->get();
        foreach ($assignedTask as $key => $task) {
           if($task->status==3)
           {
            if(InternTask::where('relatedTaskId',$task->relatedTaskId)->whereIn('status',array(2,4))->count()>0)
            {
                $assignedTask->pull($key); 
            }
           }
        }
        // dd($assignedTask);
        return $assignedTask;
    }

    public static function getCurrentTasks($empId)
    {
        $currentTask = InternTask::where('status',2)->where('takenDate',date('Y-m-d'))->where('empId',$empId)->get();
        return $currentTask;
    }

    public static function getCompletedTasks($empId)
    {
        $completedTask = InternTask::whereIn('status', array(3,4))->where('takenDate',date('Y-m-d'))->where('empId',$empId)->orderBy('startTime', 'ASC')->get();
        foreach ($completedTask as $key => $task) {
            if(InternTask::where('relatedTaskId',$task->relatedTaskId)->where('status',4)->count()>0)
            {
                $completedTask[$key]['flag']="Finished"; 
                
            }
        }
        return $completedTask;
    }
    
    public static function getAllCurrentTasks()
    {
        $currentTask = InternTask::where('status',2)->where('takenDate',date('Y-m-d'))->orderby('id','desc')->get();
        $selectedArray=array();
        foreach ($currentTask as $key => $value) {
           $selectedArray[$value['empId']]=$value;
        }

        return $selectedArray;
    }

    public static function getTaskPriorities()
    {
        $priorities =[
            0=>'High',
            1=>'Medium',
            2=>'Low',
            ];
        // dd($priorities);
        return $priorities;
    }
    // Define relationships
    public function employee()
    {
        return $this->hasOne('App\Employee', 'id', 'empId');
    }

    public function project()
    {
        return $this->hasOne('App\Project', 'id', 'projectId');
    }

    public function activity()
    {
        return $this->hasOne('App\CfgActivity', 'id', 'activityId');
    }

    public function state()
    {
        return $this->hasOne('App\CfgTaskStatus', 'id', 'status');
    }
}
