<?php

namespace App;
use App\Employee;
use App\CfgDesignations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class CfgActivity extends Model
{
   
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','name','isVisible'];
    

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];

    public static function getActivities($employee='')
    {
        if($employee==''){
            $activities = CfgActivity::where('isVisible','yes')->orderBy('name','asc')->get()->toArray();
        }
        else
        {
            $findEmployee=Employee::find($employee);
             $employeeDesignation=CfgDesignations::where('id',$findEmployee->designation)->first();
           
            if(!$employeeDesignation){
                $activities = CfgActivity::where('isVisible','yes')->orderBy('name','asc')->get()->toArray();
            }
            else{
                $activities = CfgActivity::where('isVisible','yes')->where('type',$employeeDesignation->type)->orderBy('name','asc')->get()->toArray();
            }
         }
    	
        $selectedValue=array();
        foreach ($activities as $item)
        {
            // dd($item);
            $selectedValue[$item['id']]=$item['name'];
        }
        return ($selectedValue);
    }

}
