<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowUpsVer1 extends Model
{
    protected $table="follow_ups_ver1";
    protected $fillable = [
        'leads_id', 'date','status','followup_method','remarks','time','type_of_document'
    ];

    protected $appends = ['ImageUrl'];

    public function getImageUrlAttribute()
    {
        return url('/').Storage::url('category/').$this->document_url;
    }
    
      public function followup()
   {
      return $this->belongsTo(Leads::class, 'leads_id', 'id');
   }
}
