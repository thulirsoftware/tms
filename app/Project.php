<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['projectId', 'clientId', 'projectName', 'projectDesc', 'startDate', 'status', 'endDate'];

    protected $table = 'projects';

    protected $hidden = [
    ];

    protected $dates = ['deleted_at'];
    public static function getProjects()
    {
        $projects = Project::where('status', 'open')->orderBy('id', 'asc')->get()->toArray();
        $selectedValue = array();
        foreach ($projects as $item) {
            // dd($item);
            $selectedValue[$item['id']] = $item['projectName'];
        }
        return ($selectedValue);
    }
    public function tasks()
    {
        return $this->hasMany('App\Task', 'projectId');
    }
    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
