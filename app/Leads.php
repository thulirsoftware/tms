<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 
class Leads extends Model
{
  protected $table='leads';
      protected $fillable = [
        'name', 'mobile_no', 'area', 'location','website','status','remarks', 'category' ,'date',  'whatsapp','district','next_followup_date','latest_call_date','latest_followup_status'
    ];
    
      
    
}
