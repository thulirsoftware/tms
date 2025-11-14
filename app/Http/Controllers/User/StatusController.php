<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CfgStatus;

class StatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function list()
    {
        $statuses = CfgStatus::where('version','2')->orderby('id','desc')->get();
        return view('user.status.list',compact('statuses'));

    }

    public function add()
    {
        return view('user.status.add');

    }

    public function create(Request $request)
    {
        $new = new CfgStatus();
        $new->name =  $request->name;
        $new->version =  '2';
        $new->active =  $request->active;
        $new->save();
        return redirect(route('status.list'))->with('success', 'Status added successfully');

    }

    public function edit($id)
    {
        $status = CfgStatus::where('id',$id)->first();
        return view('user.status.edit',compact('status','id'));

    }

    public function update(Request $request)
    {
        $new = CfgStatus::find($request->id);
        $new->active = $request->active;
        $new->name =  $request->name;
        $new->save();

        return redirect(route('status.list'))->with('success', 'Status updated successfully');

    }
}
