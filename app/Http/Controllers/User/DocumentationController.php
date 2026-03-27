<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FollowUps;
use Session;
use App\CfgStatus;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DocumentationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function list()
    {
        $documentations = FollowUps::where('documentation','Y')->orderby('id','desc')->get();

        return view('user.documentation.list',compact('documentations'));

    }

    public function create(Request $request)
    {
       
        $lead = Session::get('lead_id');
        $new = new FollowUps();
        $new->leads_id = $lead;
        $new->date = $request->date;
        $new->followup_method = 7;
        $new->remarks =  $request->description;
        $new->status =  $request->status;
        $new->save();
        return redirect()->back()->with('success', 'Documentation added successfully');   

    }

     public function edit($id)
    {
        $documentation = FollowUps::where('id',$id)->first();
        
        $statuses = CfgStatus::where('active','Y')->get();
        return view('user.documentation.edit',compact('documentation','id','statuses'));

    }

    public function update(Request $request)
    {
     
        $new = FollowUps::find($request->id);
        $new->date = $request->date;
        $new->status = $request->status;
        $new->followup_method = 7;
        $new->remarks =  $request->description;
        $new->save();

        return redirect(route('documentation.list'))->with('success', 'Documentation updated successfully');

    }
}
