<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CfgSource;

class SourceController extends Controller
{
    public function add()
    {
        return view('user.source.add');

    }

    public function create(Request $request)
    {
        $new = new CfgSource();
        $new->name =  $request->name;
        $new->status =  $request->status;
        $new->save();
        return redirect(route('configs'))->with('success', 'Source added successfully');

    }

    public function edit($id)
    {
        $source = CfgSource::where('id',$id)->first();
        return view('user.source.edit',compact('source','id'));

    }

    public function update(Request $request)
    {
        $new = CfgSource::find($request->id);
        $new->name =  $request->name;
        $new->status =  $request->status;
        $new->save();

        return redirect(route('configs'))->with('success', 'Source updated successfully');

    }
    public function delete($id)
    {
        $source = CfgSource::where('id',$id)->delete();
        return redirect(route('configs'))->with('success', 'Source delete successfully');

    }
}
