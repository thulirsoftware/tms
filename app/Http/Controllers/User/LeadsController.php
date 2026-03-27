<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\LeadsVer1;
use App\CfgStatus;
use View;
use Response;
use App\cfgCategory;
use DB;
use Carbon\Carbon;
use App\FollowUpsVer1;
use App\CfgArea;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Session;
use App\CfgSegment;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function list(Request $request)
    {
         Session::put('filterText','Today');
        Session::put('page',$request->page);
        if($request->search!="")
        {
           Session::put('status',$request->status);
            Session::put('area',$request->area);
            Session::put('website',$request->website);
            Session::put('name',$request->name);
            Session::put('month',$request->month);
            Session::put('sort',$request->sort);
            Session::put('segment',$request->segment);
            Session::put('domain',$request->domain);
            Session::put('start_date',$request->start_date);
            Session::put('end_date',$request->end_date);
            if($request->statusIn!=null)
            {
              Session::put('statusIn',$request->statusIn);
   
            }
            if($request->statusNotIn!=null)
            {
              Session::put('statusNotIn',$request->statusNotIn);

            }
            


             $leads = LeadsVer1::orderby('id','desc');
             
            if($request->status!=null)
            {
                if($request->status=="10")
                {
                    
                       $leadsId = $leads->where('status',$request->status)->pluck('id');
    
                     $FollowUps = FollowUpsVer1::whereIn('leads_id',$leadsId)->pluck('leads_id');
                     
                     $leads = $leads->whereNotIn('id',$FollowUps)->where('status',$request->status);
    
    
                }
                else
                {
                    $FollowUps = FollowUpsVer1::orderby('id','desc')->where('status',$request->status)->pluck('leads_id');
                
                    $leads = $leads->whereIn('id',$FollowUps);
                }

                
                
            }

           

            $statusIn = Session::get('statusIn');
            
             $statusNotIn = Session::get('statusNotIn');

            if($statusIn!=null && $statusNotIn!=null)
            {

                    $FollowUps = FollowUpsVer1::whereIn('status',$statusIn)->pluck('id');

                     
                    $FollowUps1 = FollowUpsVer1::whereIn('id',$FollowUps)->whereNotIn('status',$statusNotIn)->orderby('id','desc')->get();
                    
                    $leadsId = [];
                    foreach($FollowUps1 as $followups1)
                    {
                        $newfollow = FollowUpsVer1::where('leads_id',$followups1->leads_id)->pluck('status')->toArray();
                        $result = !empty(array_intersect($newfollow, $statusNotIn));
                        if(!$result)
                        {
                            array_push($leadsId, $followups1->leads_id);
                        }

                        
                    }

                     $leads = $leads->whereIn('id',$leadsId);
            }
            
            if($statusIn==null && $statusNotIn!=null)
            {
               

                    $leadsId = FollowUpsVer1::whereIn('status',$statusNotIn)->pluck('leads_id');
 
                      $leads = $leads->whereNotIn('id',$leadsId);
            }
            if($statusIn!=null && $statusNotIn==null)
            {
               

                    $leadsId = FollowUpsVer1::whereIn('status',$statusIn)->pluck('leads_id');
 
                      $leads = $leads->whereIn('id',$leadsId);
            }

            if($request->area!=null)
            {
                $leads = $leads->where('area',$request->area);
                


            }
            if($request->website!=null)
            {
                if($request->website=='Not Available')
                {
                    $leads = $leads->where('website','=',null);

                }
                else
                {
                    $leads = $leads->where('website','!=',null);
                }
                

            }
            if($request->name!=null)
            {
                $leads = $leads->where('name','like', '%' .$request->name. '%');
                

            }
            if($request->month!=null)
            {
                $leads = $leads->whereMonth('created_at',$request->month);

            }
             if($request->segment!=null)
            {
                $leads = $leads->where('segment',$request->segment);
                
            }
             if($request->domain!=null)
            {
                $leads = $leads->where('domain',$request->domain);
                
            }
            if($request->start_date!=null && $request->end_date==null)
            {
                     $leads = $leads->where('followup_date',$request->start_date);
            }
            if($request->start_date!=null && $request->end_date!=null)
            {
                     $leads = $leads->where('followup_date','>=',$request->start_date)->where('followup_date','<=',$request->end_date);
            }
          
            if($request->page!=null)
            {
                $leads = $leads->paginate($request->sort,['*'],'page',$request->page);
            }
            else
            {
                $leads = $leads->paginate($request->sort);
            }

            //dd($leads);
            $leadView = View::make('user.leads.components.filter',compact('leads'))->render();

            return Response::json(['leads' => $leadView]);
        }
        else
        {
            $leads = LeadsVer1::orderby('id','desc')->paginate(10);
            $statuses = CfgStatus::where('active','Y')->get();
            $areas = LeadsVer1::groupby('area')->where('area','!=',"")->get();
            $leadget = LeadsVer1::get(); 
             $segments = CfgSegment::where('status','Y')->get();
     
            
            return view('user.leads.list',compact('leads','statuses','areas','segments'));
        }
        

    }

    public function add()
    {
        $statuses = CfgStatus::where('active','Y')->get();
        $categories = cfgCategory::where('status','Y')->get();
         $segments = CfgSegment::where('status','Y')->get();
        $leads = LeadsVer1::orderby('id','desc')->get();
        return view('user.leads.add',compact('statuses','categories','leads','segments'));

    }

    public function create(Request $request)
    {
        $area = CfgArea::where('name',$request->area)->first();
        //dd($area);
        $new = new LeadsVer1();
        $new->name = $request->name;
        $new->mobile_no = $request->mobile_number;
        $new->location = $request->location;
        $new->area = $request->area;
        $new->status = $request->status;
        $new->website = $request->website;
        $new->whatsapp = $request->whatsapp;
        $new->remarks = $request->remarks;
        $new->for = $request->for;
        $new->category = $request->category;
        $new->date = date('Y-m-d');
        $new->segment = $request->segment;
        $new->domain = $request->domain;
        $new->followup_date = $request->followup_date;
        $new->source = $request->source;
        $new->save();

        return redirect(route('leads.list'))->with('success', 'Lead added successfully');

    }
    public function edit($id)
    {
        $lead = LeadsVer1::where('id',$id)->first();
        $statuses = CfgStatus::where('active','Y')->get();
        $categories = cfgCategory::where('status','Y')->get();
          $segments = CfgSegment::where('status','Y')->get();
        return view('user.leads.edit',compact('lead','id','statuses','categories','segments'));

    }

    public function update(Request $request)
    {
        $new = LeadsVer1::find($request->id);
        $new->name = $request->name;
        $new->mobile_no = $request->mobile_number;
        $new->location = $request->location;
        $new->area = $request->area;
        $new->district = $request->district;
        $new->status = $request->status;
        $new->website = $request->website;
        $new->remarks = $request->remarks;
        $new->for = $request->for;
        $new->category = $request->category;
        $new->segment = $request->segment;
        $new->domain = $request->domain;
        $new->source = $request->source;
        $new->whatsapp = $request->whatsapp;
        $new->save();
        
        $page = Session::get('page');
        if($page!=null)
        {
            return redirect(route('leads.list', ['page' => $page]))->with('success', 'Lead updated successfully');
        }
        else
        {
            return redirect(route('leads.list'))->with('success', 'Lead updated successfully');
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

    return redirect(route('leads.list'));
       
    }
    
     public function getData(Request $request)
    {
        $leads = LeadsVer1::orderby('id','desc');
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
        $source = LeadsVer1::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Leads deleted successfully');

    }
}
