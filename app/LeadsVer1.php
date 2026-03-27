<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 class LeadsVer1 extends Model
{
     protected $table='leadsver1';
      protected $fillable = [
        'name', 'mobile_no', 'area', 'location','website','status','remarks','for','category','segment','date','domain','user_id','whatsapp','approach_status','district'
    ];
    
      
    
}
