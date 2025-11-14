<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
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
    protected $fillable = ['requestDate','empId','leaveTypeId','leaveFromDate','leaveToDate','totalLeaveDays','availLeaveDays','reason','comment','status','approval'];

    protected $table = 'leaves';

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];
    public static function getLeavePriorities()
    {
        $priorities =[
            0=>'High',
            1=>'Medium',
            2=>'Low',
            ];
        return $priorities;
    }
    
}
