<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Leads;
use App\CfgStatus;
use View;
use Response;
use App\cfgCategory;
use DB;
use Carbon\Carbon;
use App\FollowUps;
use App\CfgArea;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use App\CfgSegment;
use Illuminate\Support\Arr;

class LeadsVersion2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function list(Request $request)
    {
          Session::put('filterText','Today');
        Session::put('page',$request->page);
      
           


             $leads = Leads::orderby('id','desc');
             
            if($request->status!=null)
            {
                
                $leads = $leads->where('status',$request->status);
            }

            if($request->district!=null)
            {
                $leads = $leads->where('district',$request->district);

            }
           
            if($request->name!=null)
            {
                $leads = $leads->where('name','like', '%' .$request->name. '%');

            }
            if($request->month!=null)
            {
                $leads = $leads->whereMonth('latest_call_date',$request->month);

            }
           
            if($request->start_date!=null && $request->end_date==null)
            {
                $leads = $leads->where('latest_call_date',$request->start_date);
            }
            if($request->start_date!=null && $request->end_date!=null)
            {
                $leads = $leads->where('latest_call_date','>=',$request->start_date)->where('latest_call_date','<=',$request->end_date);
            }
          
            if($request->page!=null)
            {
                $leads = $leads->paginate($request->sort,['*'],'page',$request->page);
            }
            else
            {
                $leads = $leads->paginate($request->sort);
            }
            $statuses = CfgStatus::where('active','Y')->get();
            $areas = Leads::groupby('district')->where('district','!=',"")->get();
            $leadget = Leads::get(); 
             $segments = CfgSegment::where('status','Y')->get();
             
            return view('user.leadsver2.list',compact('leads','statuses','areas','segments'));
           

    }

    public function add()
    {
        $statuses = CfgStatus::where('active','Y')->get();
        $categories = cfgCategory::where('status','Y')->get();
         $segments = CfgSegment::where('status','Y')->get();
        $leads = Leads::orderby('id','desc')->get();
        return view('user.leadsver2.add',compact('statuses','categories','leads','segments'));

    }

    public function create(Request $request)
    {
         $new = new Leads();
        $new->name = $request->name;
        $new->mobile_no = $request->mobile_number;
        $new->location = $request->location;
        $new->area = $request->area;
        $new->approach_status = $request->approach_status;
        $new->district = $request->district;
        $new->website = $request->website;
        $new->whatsapp = $request->whatsapp;
        $new->remarks = $request->remarks;
         $new->category = $request->category;
        $new->date =  $request->date;
        $new->next_followup_date = $request->date;
        $new->source = $request->source;
        $new->save();

        return redirect(route('leads.ver2.list'))->with('success', 'Lead added successfully');

    }
    public function edit($id)
    {
        $lead = Leads::where('id',$id)->first();
        $statuses = CfgStatus::where('active','Y')->get();
        $categories = cfgCategory::where('status','Y')->get();
        $segments = CfgSegment::where('status','Y')->get();
        return view('user.leadsver2.edit',compact('lead','id','statuses','categories','segments'));

    }

    public function update(Request $request)
    {
        $new = Leads::find($request->id);
        $new->name = $request->name;
        $new->mobile_no = $request->mobile_number;
        $new->location = $request->location;
        $new->area = $request->area;
        $new->district = $request->district;
        $new->approach_status = $request->approach_status;
        $new->website = $request->website;
        $new->remarks = $request->remarks;
         $new->category = $request->category;
        $new->whatsapp = $request->whatsapp;
        $new->save();
        
        $page = Session::get('page');
        if($page!=null)
        {
            return redirect(route('leads.ver2.list', ['page' => $page]))->with('success', 'Lead updated successfully');
        }
        else
        {
            return redirect(route('leads.ver2.list'))->with('success', 'Lead updated successfully');
        }

    }

    public function resetfilter(Request $request)
    {
        
    if(Session::get('name')!=null)
    {
         Session::put('name',null);
    }
    if(Session::get('status')!=null)
    {
         Session::put('status',null);
    }
    if(Session::get('month')!=null)
    {
         Session::put('month',null);
    }
    if(Session::get('website')!=null)
    {
         Session::put('website',null);
    }
    if(Session::get('sort')!=null)
    {
         Session::put('sort',null);
    }
     if(Session::get('segment')!=null)
    {
         Session::put('segment',null);
    }
     if(Session::get('domain')!=null)
    {
         Session::put('domain',null);
    }
     if(Session::get('start_date')!=null)
    {
         Session::put('start_date',null);
    }
     if(Session::get('end_date')!=null)
    {
         Session::put('end_date',null);
    }
    
     if(Session::get('statusNotIn')!=null)
    {
         Session::put('statusNotIn',null);
    }
     if(Session::get('statusIn')!=null)
    {
         Session::put('statusIn',null);
    }

    return redirect(route('leads.ver2.list'));
       
    }
    
     public function getData(Request $request)
    {
        $leads = Leads::orderby('id','desc');
        if($request->lead_name!=null)
        {
            //$leads = $leads->whereRaw("MATCH(name)AGAINST('$request->lead_name')");
             $leads =  $leads->where('name','like', '%' .$request->lead_name. '%');
        }
        if($request->mobile_no!=null)
        {
            $leads = $leads->where('mobile_no','like', '%' .$request->mobile_no. '%');
        }
        $leads = $leads->get();

        $leadView = View::make('user.leads.components.getData',compact('leads'))->render();

        return Response::json(['leads' => $leadView]);

    }
    
    public function delete($id)
    {
        $source = Leads::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Leads deleted successfully');

    }
    
    public function myleadslist(Request $request)
    {
        
        $leadsid = Leads::where('next_followup_date',date('Y-m-d'))->where('latest_followup_status','!=',6)->pluck('id')->toArray();
        $leadsIds = Leads::where('date',date('Y-m-d'))->pluck('id')->toArray();
        $all_leads =  array_merge ($leadsIds,$leadsid);
        $leads = Leads::whereIn('id',$all_leads)->orderby('id','asc');
        $leads =$leads->paginate(60);     
          
            $statuses = CfgStatus::where('active','Y')->get();
            $areas = Leads::groupby('area')->where('area','!=',"")->get();
            $leadget = Leads::get(); 
            $segments = CfgSegment::where('status','Y')->get();
             
            return view('user.myleads.list',compact('leads','statuses','areas','segments'));
            
        
        

    }
}
