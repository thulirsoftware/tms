<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class RegularTask extends Model
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
    protected $fillable = ['assignedDate','takenDate','assignedBy','empId','projectId','activityId','instruction','priority','taskType','approval'];

    protected $table = 'regular_tasks';

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];
    // protected $guarded = array();

    public static function getTaskTypes()
    {
        $types =[
            0=>'Daily',
            1=>'Weekly',
            2=>'Monthly',
            3=>'BiMonthly',
            ];
        // dd($types);
        return $types;
    }

    public static function getRegularTasks(Employee $employee)
    {
    	
        $tasks = RegularTask::where('empId','=',$employee->id)->orderby('taskType','desc')->orderby('id','desc')->get();
        foreach ($tasks as $key => $value) {
      	if($value['taskType']==0&&$value['takenDate']==date('Y-m-d'))
        	{
         		$tasks->forget($key);
        	}
		if($value['taskType']==1&&($value['takenDate']>date('Y-m-d',strtotime('1 week ago'))))
        	{
				$tasks->forget($key);
        	}
        if($value['taskType']==3&&($value['takenDate']>date('Y-m-d',strtotime('2 weeks ago'))))
        	{
				$tasks->forget($key);
        	}
        if($value['taskType']==2&&($value['takenDate']>date('Y-m-d',strtotime('1 month ago'))))
        	{
				$tasks->forget($key);    		
        	}

        }

        return $tasks;
    }
    public static function getRegularTasksForAdmin(Employee $employee)
    {
    	
        $tasks = RegularTask::where('empId','=',$employee->id)->orderby('taskType','desc')->orderby('id','desc')->get();
        return $tasks;
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

}
