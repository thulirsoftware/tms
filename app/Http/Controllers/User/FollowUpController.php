<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Leads;
use App\FollowUpsVer1;
use Session;
use App\CfgStatus;
use App\CfgFollowupMethods;
use App\cfgCategory;

class FollowUpController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function add($id)
    {
        Session::put('lead_id',$id);
        $FollowUps = FollowUpsVer1::where('leads_id',$id)->orderby('date','desc')->get();
        $statuses = CfgStatus::where('active','Y')->get();
         $categories = cfgCategory::where('status','Y')->get();
        return view('user.followup.add',compact('FollowUps','statuses','categories'));
    }

    public function create(Request $request)
    {
        $new = new FollowUpsVer1();
        $new->leads_id = $request->leads_id;
        $new->date = $request->date;
        $new->description = $request->description;
        $new->status = $request->status;
        $new->save();
        return redirect()->back()->with('success', 'Follow up added successfully');   

    }
    public function edit($id)
    {
        $FollowUp = FollowUpsVer1::where('id',$id)->first();
        $statuses = CfgStatus::where('active','Y')->get();
        $methods = CfgFollowupMethods::get();
        return view('user.followup.edit',compact('FollowUp','id','statuses','methods'));

    }

    public function update(Request $request)
    {
        $new = FollowUpsVer1::find($request->id);
        $new->date = $request->date;
        $new->remarks = $request->description;
        $new->status = $request->status;
        $new->followup_method = $request->method;
        $new->save();
        
        $new = Leads::find($new->leads_id);
        $new->followup_date = $request->followup_date;
        $new->save();

        return redirect()->back()->with('success', 'Follow up updated successfully');   

    }
    public function delete($id)
    {
        $source = FollowUpsVer1::where('id',$id)->delete();
        return redirect()->back()->with('success', 'FollowUp deleted successfully');

    }
}
