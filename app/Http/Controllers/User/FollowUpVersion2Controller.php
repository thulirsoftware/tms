<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Leads;
use App\FollowUps;
use Session;
use App\CfgStatus;
use App\CfgFollowupMethods;
use App\cfgCategory;

class FollowUpVersion2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function add($id)
    {
        Session::put('lead_id',$id);
        $FollowUps = FollowUps::where('leads_id',$id)->orderby('call_date','desc')->get();
        $statuses = CfgStatus::where('active','Y')->get();
         $categories = cfgCategory::where('status','Y')->get();
        return view('user.followupver2.add',compact('FollowUps','statuses','categories'));
    }

    public function create(Request $request)
    {
        $lead_status = CfgStatus::getLeadStatus($request->status,$request->leads_id);
        
        $new = new FollowUps();
        $new->leads_id = $request->leads_id;
        $new->call_date = $request->date;
        $new->followup_method = $request->followup_method;
        $new->assigned_to = $request->assigned_to;
        $new->assigned_by = auth()->user()->id;
        $new->remarks = $request->description;
        $new->status = $request->status;
        $new->next_followup_date = $request->next_followup_date;
        $new->save();
        

        $new = Leads::find($request->leads_id);
        $new->status = $lead_status;
        $new->latest_call_date = $request->date;
        $new->next_followup_date = $request->next_followup_date;
        $new->latest_followup_status = $request->status;
        $new->save();
        return redirect()->back()->with('success', 'Follow up added successfully');   

    }
    public function edit($id)
    {
        $FollowUp = FollowUps::where('id',$id)->first();
        $statuses = CfgStatus::where('active','Y')->get();
        $methods = CfgFollowupMethods::get();
        return view('user.followupver2.edit',compact('FollowUp','id','statuses','methods'));

    }

    public function update(Request $request)
    {
        $lead_status = CfgStatus::getLeadStatus($request->status,$request->leads_id);

        $new = FollowUps::find($request->id);
        $new->call_date = $request->date;
        $new->remarks = $request->description;
        $new->status = $request->status;
        $new->followup_method = $request->method;
        $new->assigned_to = $request->assigned_to;
        $new->assigned_by = auth()->user()->id;
        $new->next_followup_date = $request->next_followup_date;
        $new->save();
 
        $new = Leads::find($request->leads_id);
        $new->status = $lead_status;
        $new->latest_call_date = $request->date;
        $new->next_followup_date = $request->next_followup_date;
        $new->latest_followup_status = $request->status;
        $new->save();

       

        return redirect()->back()->with('success', 'Follow up updated successfully');   

    }
    public function delete($id)
    {
        $source = FollowUps::where('id',$id)->delete();
        return redirect()->back()->with('success', 'FollowUp deleted successfully');

    }
}
