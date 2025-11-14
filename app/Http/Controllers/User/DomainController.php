<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CfgDomain;

class DomainController extends Controller
{
    public function add()
    {
        return view('user.domain.add');

    }

    public function create(Request $request)
    {
        $new = new cfgDomain();
        $new->name =  $request->name;
        $new->status =  $request->status;
        $new->save();
        return redirect(route('configs'))->with('success', 'Domain added successfully');

    }

    public function edit($id)
    {
        $domain = cfgDomain::where('id',$id)->first();
        return view('user.domain.edit',compact('domain','id'));

    }

    public function update(Request $request)
    {
        $new = cfgDomain::find($request->id);
        $new->name =  $request->name;
        $new->status =  $request->status;
        $new->save();

        return redirect(route('configs'))->with('success', 'Domain updated successfully');

    }
    
    public function delete($id)
    {
        $source = cfgDomain::where('id',$id)->delete();
        return redirect(route('configs'))->with('success', 'Domain deleted successfully');

    }
}
