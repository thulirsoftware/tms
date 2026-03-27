<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leads;
use Illuminate\Support\Arr;
use App\FollowUps;
use App\LeadsVer1;

class ReportsController extends Controller
{
    public function areas()
    {
        $leads = LeadsVer1::select('area', \DB::raw('count(*) as total'))->groupby('area')->orderby('total','desc')->paginate(10);

        

         return view('user.reports.areas',compact('leads'));
    }

    public function filter(Request $request)
    { 
        $leads = LeadsVer1::orderby('id','desc');
        if($request->search!=null)
        {

            $leads = $leads->orwhere('name','like',"%{$request->search}%")
            ->orwhere('area','like',"%{$request->search}%")
            ->orwhere('location','like',"%{$request->search}%")
            ->orwhere('mobile_no','like',"%{$request->search}%");
        }
       
        if($request->domain!=null)
        {

            $leads = $leads->where('domain',$request->domain);
        }

        if($request->segment!=null)
        {
            $leads = $leads->where('segment',$request->segment);
        }
        if($request->area!=null && $request->search==null)
        {
            $leads = $leads->where('area',$request->area);
        }
        if($request->name!=null && $request->search==null)
        {
            $nameIdArray = [];
            $nameArray = explode(',', $request->name);
            $nameArray = array_map('trim', $nameArray);

            foreach($nameArray as $Namearray)
            {
                $ids = LeadsVer1::Where('name','like', "%{$Namearray}%")->pluck('id')->toArray();
                 array_push($nameIdArray,$ids);
            }
            $array = Arr::collapse( $nameIdArray );
            $leads = $leads->WhereIn('id', $array);
        }

        if($request->mobile_no!=null && $request->search==null)
        {
            $mobileNoIdArray = [];
            $mobileNoArray = explode(',', $request->mobile_no);
            $mobileNoArray = array_map('trim', $mobileNoArray);

            foreach($mobileNoArray as $MobileNoarray)
            {
                $ids = LeadsVer1::Where('mobile_no','like', "%{$MobileNoarray}%")->pluck('id')->toArray();
                 array_push($mobileNoIdArray,$ids);
            }
            $array = Arr::collapse( $mobileNoIdArray );
            
            $leads = $leads->WhereIn('id', $array);
        }
        
        if($request->location!=null && $request->search==null)
        {
            $locationIdArray = [];
            $locationArray = explode(',', $request->location);
            $locationArray = array_map('trim', $locationArray);
            foreach($locationArray as $locationarray)
            {
                $ids = LeadsVer1::Where('location','like', "%{$locationarray}%")->pluck('id')->toArray();
                 array_push($locationIdArray,$ids);
            }
            $array = Arr::collapse( $locationIdArray );
            $leads = $leads->WhereIn('id', $array);
        }
        
        if($request->status!=null)
        {
            $leadsId= $leads->pluck('id') ;
            $leads = $leads->WhereIn('status',  $request->status);
        }
        
        if($request->daterange!=null)
        {
             
            $dates = explode(' - ' ,$request->daterange);
            $start_date = date('Y-m-d', strtotime($dates[0]));
            $end_date = date('Y-m-d', strtotime($dates[1]));
            $leads = $leads->whereBetween('date',  [$start_date,$end_date]);
        }
        
       
        $leads_filter = LeadsVer1::orderby('id','desc')->get();
        $leads = $leads->paginate(19);
        return view('user.reports.list',compact('leads','leads_filter'));
    }

    public function segments()
    {
        $leads = LeadsVer1::select('segment', \DB::raw('count(*) as total'))->groupby('segment')->orderby('total','desc')->get();
         return view('user.reports.segment',compact('leads'));
    }

    public function domains()
    {
        $leads = LeadsVer1::select('domain', \DB::raw('count(*) as total'))->groupby('domain')->orderby('total','desc')->get();
         return view('user.reports.domain',compact('leads'));
    }

    public function uploadExcel(Request $request)
    {
        dd($request);
    }
}
