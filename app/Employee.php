<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{


    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['empId', 'name', 'email', 'designation', 'mobile', 'gender', 'dob', 'address', 'city', 'state', 'motherTongue', 'qualification', 'expLevel', 'expYear', 'expMonth', 'bankAccountName', 'bankAccountNo', 'bankName', 'bankBranch', 'bankIfscCode', 'regDate', 'joinDate', 'resignDate', 'empSatus'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];

    public static function getEmpId()
    {
        $regnNo = $defaultStartNo = 1001;
        $lastRecord = Employee::orderBy('id', 'desc')->first();

        if ($lastRecord != null) {

            $regnNo = str_pad(++$lastRecord->id, 4, 1000, STR_PAD_LEFT);
            // dd($regnNo);
        }

        return $regnNo;
    }
    public static function getEmployeeList()
    {
        $selectedArray = array();
        $employees = Employee::where('deleted_at', null)->get();
        foreach ($employees as $key => $value) {
            $selectedArray[$value->id] = $value->name;
        }
        return $selectedArray;
    }

    public function designate()
    {
        return $this->hasOne('App\CfgDesignations', 'id', 'designation');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'empId', 'empId');
    }

    public function designations()
    {
        return $this->hasMany('App\CfgDesignations', 'id', 'designation');
    }

}
