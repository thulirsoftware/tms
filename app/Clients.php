<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Clients extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'location',
        'isvisible',
        'status'
    ];
    public function projects()
    {
        return $this->hasMany(Project::class, 'clientId', 'id');
    }

    
    public function services()
    {
        return $this->hasMany(ClientServices::class, 'client_id', 'id');
    }

}
