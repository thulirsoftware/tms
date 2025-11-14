<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientProjects extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'project_name',
        'project_desc',
        'start_date',
        'end_date',
        'status',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
