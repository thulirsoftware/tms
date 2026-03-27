<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CfgSegment extends Model
{
    protected $fillable = [
         'name','status','domain_id'
    ];
}
