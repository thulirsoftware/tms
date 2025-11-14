<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientServices extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'isvisible',
        'status',
        'client_id'
    ];

    public function client()
    {
        return $this->belongsTo(Clients::class);
    }
}
