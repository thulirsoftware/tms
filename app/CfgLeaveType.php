<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
class CfgLeaveType extends Model
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
   
    protected $fillable = ['name','maxLeave','leaveFor'];
    protected $table = 'cfg_leave_types';

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];

    
    public static function getLeaveTypeList()
    {
        $selectedArray=array();
        $statuses = CfgLeaveType::where('isVisible','yes')->get();
        foreach ($statuses as $key => $value) {
            // $selectedArray[$value->id]=$value->name.' ('.$value['maxLeave'].' Days/'.ucfirst($value['leaveFor']).')';
            
            $selectedArray[$value->id]=$value->name;
        }
        return $selectedArray;
    }
}
