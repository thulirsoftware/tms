<?php

namespace App;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


use App\Notifications\TaskReminder;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected static function booted()
    {
        static::deleting(function ($user) {
            if ($user->employee) {
                $user->employee->delete();
            }
        });
    }
    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'empId'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function employee()
    {
        return $this->hasOne(Employee::class, 'empId', 'empId');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function hasRole($role)
    {
        // Super Admin (id=1) always true
        if ($this->id === 1) {
            return true;
        }

        return $this->roles()->where('name', $role)->exists();
    }
    public function hasPermission($permission)
    {
        // Super Admin (id=1) always true
        if ($this->id === 1) {
            return true;
        }

        return $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name')
            ->contains($permission);
    }



}
