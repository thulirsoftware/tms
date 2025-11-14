<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CfgSegment;

class SegmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
   
    public function add()
    {
        return view('user.segment.add');

    }

    public function create(Request $request)
    {
        $new = new CfgSegment();
        $new->name =  $request->name;
        $new->status =  $request->status;
         $new->domain_id =  $request->domain;
        $new->save();
        return redirect(route('configs'))->with('success', 'Segment added successfully');

    }

    public function edit($id)
    {
        $segment = CfgSegment::where('id',$id)->first();
        return view('user.segment.edit',compact('segment','id'));

    }

    public function update(Request $request)
    {
        $new = CfgSegment::find($request->id);
        $new->name =  $request->name;
        $new->status =  $request->status;
         $new->domain_id =  $request->domain;
        $new->save();

        return redirect(route('configs'))->with('success', 'Segment updated successfully');

    }
    public function delete($id)
    {
        $source = CfgSegment::where('id',$id)->delete();
        return redirect(route('configs'))->with('success', 'Segment deleted successfully');

    }
}
