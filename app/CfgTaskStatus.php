<?php


namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class CfgTaskStatus extends Model
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
    protected $fillable = ['name','isVisible'];

    protected $table = 'cfg_task_statuses';

    

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];

    
    public static function getStatusList()
    {
        $selectedArray=array();
        $statuses = CfgTaskStatus::where('isVisible','yes')->get();
        foreach ($statuses as $key => $value) {
            $selectedArray[$value->id]=$value->name;
        }
        return $selectedArray;
    }
}
