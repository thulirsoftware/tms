<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = ['assignedDate','takenDate','assignedBy','relatedTaskId','empId','projectId','activityId','instruction','priority','comment','startTime','endTime','is_more_than_8','end_updated_time', 'approval','status'];

    protected $table = 'tasks';

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];
    
    // public static function getPendingTasks($empId)
    // {
    //     $pendingTask = Task::where('status',3)->where('empId',$empId)->get();
    //     return $pendingTask;
    // }

    public static function getAssignedTasks($empId)
    {
        $assignedTask = Task::whereIn('status',array(1,3))->where('empId',$empId)->whereRaw('id = relatedTaskId')->get();
        foreach ($assignedTask as $key => $task) {
           if($task->status==3)
           {
            if(Task::where('relatedTaskId',$task->relatedTaskId)->whereIn('status',array(2,4))->count()>0)
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
        $currentTask = Task::where('status',2)->where('empId',$empId)->orderby('id','desc')->limit(1)->get();
        return $currentTask;
    }

    public static function getCompletedTasks($empId)
    {
        $completedTask = Task::whereIn('status', array(3,4))->where('takenDate',date('Y-m-d'))->where('empId',$empId)->orderBy('startTime', 'ASC')->get();
        foreach ($completedTask as $key => $task) {
            if(Task::where('relatedTaskId',$task->relatedTaskId)->where('status',4)->count()>0)
            {
                $completedTask[$key]['flag']="Finished"; 
                
            }
        }
        return $completedTask;
    }
    
    public static function getAllCurrentTasks()
    {
        $currentTask = Task::where('status',2)->where('takenDate',date('Y-m-d'))->orderby('id','desc')->get();
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

    public function employee() {
        return $this->hasOne('App\Employee','id','empId');
    }
    
    public function project()
    {
        return $this->hasOne('App\Project','id','projectId');
    }
    
    public function activity()
    {
        return $this->hasOne('App\CfgActivity','id','activityId');
    }

    public function state()
    {
        return $this->hasOne('App\CfgTaskStatus','id','status');
    }



}
