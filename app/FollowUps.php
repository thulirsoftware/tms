<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FollowUps extends Model
{
    protected $fillable = [
        'leads_id', 'date','status','followup_method','remarks','assigned_to','assigned_by','followup_date','next_followup_date'
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
