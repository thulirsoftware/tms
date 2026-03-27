<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeavePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'empId',
        'name',
        'email',
        'requestDate',
        'permissionDate',
        'fromTime',
        'toTime',
        'totalHours',
        'reason',
        'approval',
    ];
    public function employee()
    {
        // empId in leave_permissions → empId in employees
        return $this->belongsTo(Employee::class, 'empId', 'empId');
    }
}
