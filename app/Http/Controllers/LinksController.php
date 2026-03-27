<?php

namespace App\Http\Controllers;

use Auth;
use App\Employee;
use Illuminate\Http\Request;
use App\Links;
use DB;
use Carbon\Carbon;
use App\User;
use View;
use Response;
use Session;

class LinksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list()
    {
        $userId = Auth::id();

        $links = Links::query()
            ->where(function ($q) use ($userId) {
                $q->where('senderId', $userId)
                    ->orWhere('receiverId', $userId);
            })
            ->where('deleted_at', 'N') // show only non-deleted
            ->orderBy('id', 'desc')
            ->with(['sender', 'receiver'])
            ->get();

        $employees = User::all();

        return view('links.list', compact('links', 'employees'));
    }



    public function add()
    {
        $links = Links::get();
        $employees = User::all()->pluck('name', 'id');
        return view('links.add', compact('links', 'employees'));

    }

    public function create(Request $request)
    {
        $request['senderId'] = Auth::user()->id;
        $Link = Links::create($request->all());
        session()->flash('success', 'Links created Succesfully');
        return redirect('/Links');

    }

    public function edit($id)
    {
        $link = Links::where('id', $id)->first();
        $employees = User::all()->pluck('name', 'id');
        return view('links.edit', compact('link', 'employees'));

    }

    public function update(Request $request)
    {
        $link = Links::where('id', $request->Id)->first();
        $link->update($request->all());
        session()->flash('success', 'Links updated Succesfully');
        return redirect('/Links');

    }

    public function ListLinks()
    {
        $links = Links::orderby('id', 'desc');
        if (Session::get('senderId') != null) {
            $links = $links->where('senderId', Session::get('senderId'));
        }
        if (Session::get('send_date') != null) {
            $date = date('Y-m-d', strtotime(Session::get('send_date')));
            $links = $links->where('date', $date);
        }
        if (Session::get('deleted') != null) {
            $links = $links->where('deleted_at', Session::get('deleted'));
        }


        $links = $links->where('deleted_at', '=', 'N')->get();
        $employees = User::get();
        return view('admin.links.list', compact('links', 'employees'));

    }

    public function Adminadd()
    {
        $links = Links::get();
        $employees = User::all()->pluck('name', 'id');
        return view('admin.links.add', compact('links', 'employees'));

    }

    public function Admincreate(Request $request)
    {
        $request['senderId'] = Auth::user()->id;
        $Link = Links::create($request->all());
        session()->flash('success', 'Links created Succesfully');
        return redirect('/Admin/Links');

    }

    public function Adminedit($id)
    {
        $link = Links::where('id', $id)->first();
        $employees = User::all()->pluck('name', 'id');
        return view('admin.links.edit', compact('link', 'employees'));

    }

    public function Adminupdate(Request $request)
    {
        $link = Links::where('id', $request->Id)->first();
        $link->update($request->all());
        session()->flash('success', 'Links updated Succesfully');
        return redirect('/Admin/Links');

    }

    public function delete(Request $request, $id)
    {
        $request->request->add(['deleted_at' => 'Y']); //add request

        $link = Links::where('id', $id)->first();
        $link->update($request->all());
        return redirect()->back()->with('success', 'Links deleted successfully');

    }

    public function search(Request $request)
    {

        $links = Links::orderby('id', 'desc');
        if ($request->senderId != null) {
            Session::put('senderId', $request->senderId);
            if (Auth::user()->type != 'admin') {
                Session::put('UserSenderId', $request->senderId);
                $links = $links->where('senderId', $request->senderId)->where('receiverId', Auth()->user()->id);
            } else {
                $links = $links->where('senderId', $request->senderId);
            }


        }
        if ($request->send_date != null) {
            Session::put('send_date', $request->send_date);
            $date = date('Y-m-d', strtotime($request->send_date));
            $links = $links->where('date', $date);
            if (Auth::user()->type != 'admin') {
                Session::put('UserSendDate', $request->send_date);
            }

        }
        if ($request->deleted != null) {
            Session::put('deleted', $request->deleted);
            $links = $links->where('deleted_at', $request->deleted);
            if (Auth::user()->type != 'admin') {
                Session::put('UserDeleted', $request->deleted);
            }
        }
        if ($request->deleted == null) {
            $links = $links->where('deleted_at', 'N');
        }

        $links = $links->get();
        if (Auth::user()->type != 'admin') {
            $linksView = View::make('links.components.getData', compact('links'))->render();
        } else {
            $linksView = View::make('admin.links.components.getData', compact('links'))->render();
        }


        return Response::json(['links' => $linksView]);
    }

    public function resetfilterAdmin()
    {
        if (Session::get('senderId') != null) {
            Session::put('senderId', null);
        }
        if (Session::get('send_date') != null) {
            Session::put('send_date', null);
        }

        if (Session::get('deleted') != null) {
            Session::put('deleted', null);
        }

        return redirect(route('admin.links.list'));
    }

    public function resetfilter()
    {
        if (Session::get('UserSenderId') != null) {
            Session::put('UserSenderId', null);
        }
        if (Session::get('UserSendDate') != null) {
            Session::put('UserSendDate', null);
        }

        if (Session::get('UserDeleted') != null) {
            Session::put('UserDeleted', null);
        }

        return redirect(route('links.list'));
    }

}
