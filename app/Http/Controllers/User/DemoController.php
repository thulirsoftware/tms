<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FollowUps;
use Session;
use App\CfgStatus;
use App\Leads;

class DemoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function list()
    {
        $demos = FollowUps::where('demo','Y')->orderby('id','desc')->get();
        return view('user.demo.list',compact('demos'));

    }

    public function create(Request $request)
    {
        $lead = Session::get('lead_id');
        $new = new FollowUps();
        $new->leads_id = $lead;
        $new->date =  $request->date;
        $new->time =  $request->time;
        $new->status =  $request->status;
        $new->remarks =  $request->description;
        $new->followup_method = 5;
        $new->save();
        
        $new = Leads::find($lead);
        $new->followup_date = $request->followup_date;
        $new->save();
        return redirect()->back()->with('success', 'Demo added successfully');   

    }

    public function edit($id)
    {
        $demo = FollowUps::where('id',$id)->first();
        $statuses = CfgStatus::where('active','Y')->get();
        return view('user.demo.edit',compact('demo','id','statuses'));

    }

    public function update(Request $request)
    {
        $new = FollowUps::find($request->id);
        $new->date = $request->date;
        $new->status = $request->status;
        $new->remarks =  $request->description;
         $new->followup_method = 5;
        $new->save();

        return redirect(route('demo.list'))->with('success', 'Demo updated successfully');

    }
}
