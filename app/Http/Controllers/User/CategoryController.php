<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\cfgCategory;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
   
    public function add()
    {
        return view('user.category.add');

    }

    public function create(Request $request)
    {
        $new = new cfgCategory();
        $new->for =  $request->for;
        $new->category =  $request->category;
        $new->status =  $request->status;
        $new->save();
        return redirect(route('configs'))->with('success', 'Category added successfully');

    }

    public function edit($id)
    {
        $category = cfgCategory::where('id',$id)->first();
        return view('user.category.edit',compact('category','id'));

    }

    public function update(Request $request)
    {
        $new = cfgCategory::find($request->id);
        $new->for =  $request->for;
        $new->category =  $request->category;
        $new->status =  $request->status;
        $new->save();

        return redirect(route('configs'))->with('success', 'Category updated successfully');

    }
    public function delete($id)
    {
        $source = cfgCategory::where('id',$id)->delete();
        return redirect(route('configs'))->with('success', 'Category delete successfully');

    }
}
