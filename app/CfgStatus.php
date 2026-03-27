<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Leads;

class CfgStatus extends Model
{
    protected $fillable = [
        'name', 'active'
    ];

    public static function getLeadStatus($status,$leadsId)
    {
        $FollowUps = FollowUps::whereIn('status',[2,13,14])->where('leads_id',$leadsId)->get();
        $lead_status = "";
        if($status==6)
        {
            $lead_status = "N";
        }
        else if($status==2)
        {
            $lead_status = "P";
        }
        else if($status==13)
        {
            $lead_status = "P";
        }
        else if($status==14)
        {
            $lead_status = "P";
        }
         
        else
        {
            $lead_status = "I";
        }
        return $lead_status;
    }
}
