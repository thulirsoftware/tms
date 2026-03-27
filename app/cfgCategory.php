<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cfgCategory extends Model
{
    protected $fillable = [
        'for', 'category','status'
    ];
}
