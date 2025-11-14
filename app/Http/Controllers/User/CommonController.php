<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FollowUpsVer1;
use Session;
use App\cfgCategory;
use App\CfgDomain;
use App\CfgSegment;
use App\LeadsVer1;
use App\Project;
use App\CfgSource;

class CommonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function callCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 1;
        $new->save();
        
        $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        
        return redirect()->back()->with('success', 'Call added successfully');   

    }

    public function messageCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 2;
        $new->save();
        
         $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        return redirect()->back()->with('success', 'Message added successfully');   

    }

    public function visitCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 4;
        $new->save();
        
        $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        return redirect()->back()->with('success', 'Direct visit  added successfully');   

    }

    public function mailCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 3;
        $new->save();
        
        $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        
        return redirect()->back()->with('success', 'Mail added successfully');   

    }

   

    public function meetingCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 6;
        $new->save();
        
         $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        return redirect()->back()->with('success', 'Meeting   added successfully');   

    }
    
    public function documentCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 7;
        $new->type_of_document =  $request->type_of_document;
        $new->save();
        
         $new = LeadsVer1::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        return redirect()->back()->with('success', 'Documentation added successfully');   

    }
    
    public function conversionCreate(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUpsVer1();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->category_id =  $request->category;
        $new->remarks =  $request->description;
        $new->followup_method = 8;
        $new->status=14;
        $new->save();
        
        
        return redirect()->back()->with('success', 'Conversion added successfully');   

    }

     public function configs()
    {
        $categories = cfgCategory::orderby('id','desc')->get();
        $segments = CfgSegment::orderby('id','desc')->get();
        $domains = CfgDomain::orderby('id','desc')->get();
        $sources = CfgSource::orderby('id','desc')->get();
        return view('user.configs.configs',compact('categories','segments','domains','sources'));

    }
}
