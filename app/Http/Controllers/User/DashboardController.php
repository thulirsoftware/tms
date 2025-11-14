<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FollowUps;
use App\LeadsVer1;
use View;
use Response;
use Session;
use DB;
use App\CfgSegment;
use App\CfgDomain;
use App\CfgStatus;

class DashboardController extends Controller
{
    public function list(Request $request)
    {
        
       $callsCount = FollowUps::where('followup_method','1');
        $messageCount = FollowUps::where('followup_method','2');
        $mailCount = FollowUps::where('followup_method','3');
        $directVisitCount = FollowUps::where('followup_method','4');
        $demoCount = FollowUps::where('followup_method','5');
        $meetingCount = FollowUps::where('followup_method','6');
        $documentationCount = FollowUps::where('followup_method','7');
        $filterText = '';
        $date_raw = '';
        $leads = LeadsVer1::orderby('id','desc');
        $groupBY = LeadsVer1::orderby('domain','asc');

        $demoCompleted = FollowUps::where('status','3');
        $conversionPossible = FollowUps::where('status','2');
        $Didntpick = FollowUps::where('status','like', '4');
        $Fixedappointment = FollowUps::where('status','5');
        $Noneednow = FollowUps::where('status','6');
        $Pickedbutnoresponse = FollowUps::where('status','7');
        $Softwareprovideravailable = FollowUps::where('status','8');
        $Talkedtojewellery = FollowUps::where('status','9');
        $filterText = Session::get('filterText');

        $followupStatus = FollowUps::orderby('id','desc');

        if($filterText=='Today')
        {
            Session::put('filterText','Today');
            $filterText = 'Today';
            $date = date('Y-m-d');
            $leads = $leads->whereDate('created_at',date('Y-m-d'));

            $groupBY = $groupBY->whereDate('created_at',date('Y-m-d'));
            
            $callsCount = $callsCount->whereDate('created_at',$date);
            $messageCount = $messageCount->whereDate('created_at',$date);
            $mailCount = $mailCount->whereDate('created_at',$date);
            $directVisitCount = $directVisitCount->whereDate('created_at',$date);
            $demoCount = $demoCount->whereDate('created_at',$date);
            $meetingCount = $meetingCount->whereDate('created_at',$date);
            $documentationCount = $documentationCount->whereDate('created_at',$date);

            $followupStatus = $followupStatus->whereDate('created_at',$date);
           

        }
        if($filterText=='Yesterday')
        {
            Session::put('filterText','Yesterday');
            $filterText = 'Yesterday';
            $date = date('Y-m-d', strtotime('-1 day'));

            $leads = $leads->whereDate('created_at',$date);
            $groupBY = $groupBY->whereDate('created_at',$date);
            
            $callsCount = $callsCount->whereDate('created_at',$date);
            $messageCount = $messageCount->whereDate('created_at',$date);
            $mailCount = $mailCount->whereDate('created_at',$date);
            $directVisitCount = $directVisitCount->whereDate('created_at',$date);
            $demoCount = $demoCount->whereDate('created_at',$date);
            $meetingCount = $meetingCount->whereDate('created_at',$date);
            $documentationCount = $documentationCount->whereDate('created_at',$date);

            $followupStatus = $followupStatus->whereDate('created_at',$date);
           

        }
        if($filterText=='This Week')
        {
            Session::put('filterText','This Week');
            $firstday = date('Y-m-d', strtotime("this week"));
            $date = date_create($firstday);
            date_add($date, date_interval_create_from_date_string("7 days"));
            $lastDay = date_format($date, "Y-m-d");

            $filterText = "This Week";
            $leads = $leads->whereBetween('date',[$firstday,$lastDay]);
            $groupBY = $groupBY->whereBetween('date',[$firstday,$lastDay]);
            $callsCount = $callsCount->whereBetween('date',[$firstday,$lastDay]);
            $messageCount = $messageCount->whereBetween('date',[$firstday,$lastDay]);
            $mailCount = $mailCount->whereBetween('date',[$firstday,$lastDay]);
            $directVisitCount = $directVisitCount->whereBetween('date',[$firstday,$lastDay]);
            $demoCount = $demoCount->whereBetween('date',[$firstday,$lastDay]);
            $meetingCount = $meetingCount->whereBetween('date',[$firstday,$lastDay]);
            $documentationCount = $documentationCount->whereBetween('date',[$firstday,$lastDay]);

            $followupStatus = $followupStatus->whereBetween('date',[$firstday,$lastDay]);
            

        }
        if($filterText=='This Month')
        {
            Session::put('filterText','This Month');
            $firstday = date('Y-m-01');
            $lastDay = date('Y-m-t');

            $filterText = "This Month";
            $leads = $leads->whereBetween('date',[$firstday,$lastDay]);
            $groupBY = $groupBY->whereBetween('date',[$firstday,$lastDay]);
            $callsCount = $callsCount->whereBetween('date',[$firstday,$lastDay]);
            $messageCount = $messageCount->whereBetween('date',[$firstday,$lastDay]);
            $mailCount = $mailCount->whereBetween('date',[$firstday,$lastDay]);
            $directVisitCount = $directVisitCount->whereBetween('date',[$firstday,$lastDay]);
            $demoCount = $demoCount->whereBetween('date',[$firstday,$lastDay]);
            $meetingCount = $meetingCount->whereBetween('date',[$firstday,$lastDay]);
            $documentationCount = $documentationCount->whereBetween('date',[$firstday,$lastDay]);

            $followupStatus = $followupStatus->whereBetween('date',[$firstday,$lastDay]);

        }
        if($filterText=='All Time')
        {
            
            Session::put('filterText','All Time');
            $leads = $leads;
            $groupBY = $groupBY;

        }
         $segment = Session::get('segment');
         $domain = Session::get('domain');
        if($segment!=null && $domain!=null)
        {

            Session::put('segment',$segment);
            Session::put('domain',$domain);
            $leads = $leads->where('segment',$segment);
            $groupBY = $groupBY->where('segment',$segment);
            $leads_id = LeadsVer1::orderby('id','desc')->where('segment', $segment)->pluck('id');
            $callsCount = $callsCount->whereIn('leads_id',$leads_id);
            $messageCount = $messageCount->whereIn('leads_id',$leads_id);
            $mailCount = $mailCount->whereIn('leads_id',$leads_id);
            $directVisitCount = $directVisitCount->whereIn('leads_id',$leads_id);
            $demoCount = $demoCount->whereIn('leads_id',$leads_id);
            $meetingCount = $meetingCount->whereIn('leads_id',$leads_id);
            $documentationCount = $documentationCount->whereIn('leads_id',$leads_id);

            $followupStatus = $followupStatus->whereIn('leads_id',$leads_id);

        }
        if($segment==null && $domain!=null)
        {

            Session::put('domain', $domain);
            $leads = $leads->where('domain', $domain);
            $groupBY = $groupBY->where('domain',$domain);
            $leads_id = LeadsVer1::orderby('id','desc')->where('domain', $domain)->pluck('id');
            $callsCount = $callsCount->whereIn('leads_id',$leads_id);
            $messageCount = $messageCount->whereIn('leads_id',$leads_id);
            $mailCount = $mailCount->whereIn('leads_id',$leads_id);
            $directVisitCount = $directVisitCount->whereIn('leads_id',$leads_id);
            $demoCount = $demoCount->whereIn('leads_id',$leads_id);
            $meetingCount = $meetingCount->whereIn('leads_id',$leads_id);
            $documentationCount = $documentationCount->whereIn('leads_id',$leads_id);

            $followupStatus = $followupStatus->whereIn('leads_id',$leads_id);

        }
       
        $groupBY = $groupBY->groupBy('domain')->groupBy('segment')->select('domain','segment', DB::raw('count(segment) as total'))->get();

       
        $leads1 = $leads->get();
        $followupsId = [];

        foreach($leads1 as $lead)
        {
           // dd($lead);
            $followup = FollowUps::select(DB::raw('id, max(id) as id'))->where('leads_id',$lead->id)->groupBy('leads_id')->orderby('id','desc')->first();
            if($followup!=null)
            {
                array_push($followupsId, $followup->id);
            }
            

           
        }
        $statuses = CfgStatus::get();
       
        $chart =[];
        $chartCount =[];
        foreach($statuses as $status)
        {
            $followup1 = FollowUps::where('status',$status->id)->whereIn('id',$followupsId)->get();
            $newCompete = array('name'=>$status->name,'count'=>$followup1->count());
            array_push($chart, $newCompete);
            array_push($chartCount, $followup1->count());
        }

           $callsCount = $callsCount->count();
        $messageCount = $messageCount->count();
        $mailCount = $mailCount->count();
        $directVisitCount = $directVisitCount->count();
        $demoCount = $demoCount->count();
        $meetingCount = $meetingCount->count();
        $documentationCount = $documentationCount->count();

        $segments = CfgSegment::get();
        $domains = CfgDomain::get();
        $leads = $leads->count();


        
        return view('user.dashboard',compact('leads','callsCount','messageCount','mailCount','directVisitCount','demoCount','meetingCount','documentationCount','filterText','segments','domains','groupBY','chart'));
    }


    public function filter(Request $request)
    {
        
        $callsCount = FollowUps::where('followup_method','1');
        $messageCount = FollowUps::where('followup_method','2');
        $mailCount = FollowUps::where('followup_method','3');
        $directVisitCount = FollowUps::where('followup_method','4');
        $demoCount = FollowUps::where('followup_method','5');
        $meetingCount = FollowUps::where('followup_method','6');
        $documentationCount = FollowUps::where('followup_method','7');
        $filterText = '';
        $date_raw = '';
        $leads = LeadsVer1::orderby('id','desc');

        $demoCompleted = FollowUps::where('status','3');
        $conversionPossible = FollowUps::where('status','2');
        $Didntpick = FollowUps::where('status','like', '4');
        $Fixedappointment = FollowUps::where('status','5');
        $Noneednow = FollowUps::where('status','6');
        $Pickedbutnoresponse = FollowUps::where('status','7');
        $Softwareprovideravailable = FollowUps::where('status','8');
        $Talkedtojewellery = FollowUps::where('status','9');

        if($request->filterText=='Today')
        {
            Session::put('filterText','Today');
            $filterText = 'Today';
            $date = date('Y-m-d');
            $leads = $leads->whereDate('created_at',date('Y-m-d'));
            
            $callsCount = $callsCount->whereDate('created_at',$date);
            $messageCount = $messageCount->whereDate('created_at',$date);
            $mailCount = $mailCount->whereDate('created_at',$date);
            $directVisitCount = $directVisitCount->whereDate('created_at',$date);
            $demoCount = $demoCount->whereDate('created_at',$date);
            $meetingCount = $meetingCount->whereDate('created_at',$date);
            $documentationCount = $documentationCount->whereDate('created_at',$date);

            $demoCompleted = $demoCompleted->whereDate('created_at',$date);
            $conversionPossible = $conversionPossible->whereDate('created_at',$date);
            $Didntpick = $Didntpick->whereDate('created_at',$date);
            $Fixedappointment = $Fixedappointment->whereDate('created_at',$date);
            $Noneednow = $Noneednow->whereDate('created_at',$date);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereDate('created_at',$date);
            $Softwareprovideravailable = $Softwareprovideravailable->whereDate('created_at',$date);
            $Talkedtojewellery = $Talkedtojewellery->whereDate('created_at',$date);

        }
        if($request->filterText=='Yesterday')
        {
            Session::put('filterText','Yesterday');
            $filterText = 'Yesterday';
            $date = date('Y-m-d', strtotime('-1 day'));

            $leads = $leads->whereDate('created_at',$date);
            $callsCount = $callsCount->whereDate('created_at',$date);
            $messageCount = $messageCount->whereDate('created_at',$date);
            $mailCount = $mailCount->whereDate('created_at',$date);
            $directVisitCount = $directVisitCount->whereDate('created_at',$date);
            $demoCount = $demoCount->whereDate('created_at',$date);
            $meetingCount = $meetingCount->whereDate('created_at',$date);
            $documentationCount = $documentationCount->whereDate('created_at',$date);

            $demoCompleted = $demoCompleted->whereDate('created_at',$date);
            $conversionPossible = $conversionPossible->whereDate('created_at',$date);
            $Didntpick = $Didntpick->whereDate('created_at',$date);
            $Fixedappointment = $Fixedappointment->whereDate('created_at',$date);
            $Noneednow = $Noneednow->whereDate('created_at',$date);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereDate('created_at',$date);
            $Softwareprovideravailable = $Softwareprovideravailable->whereDate('created_at',$date);
            $Talkedtojewellery = $Talkedtojewellery->whereDate('created_at',$date);

        }
        if($request->filterText=='This Week')
        {
            Session::put('filterText','This Week');
            $firstday = date('Y-m-d', strtotime("this week"));
            $date = date_create($firstday);
            date_add($date, date_interval_create_from_date_string("7 days"));
            $lastDay = date_format($date, "Y-m-d");

            $filterText = "This Week";
            $leads = $leads->whereBetween('date',[$firstday,$lastDay]);
            $callsCount = $callsCount->whereBetween('date',[$firstday,$lastDay]);
            $messageCount = $messageCount->whereBetween('date',[$firstday,$lastDay]);
            $mailCount = $mailCount->whereBetween('date',[$firstday,$lastDay]);
            $directVisitCount = $directVisitCount->whereBetween('date',[$firstday,$lastDay]);
            $demoCount = $demoCount->whereBetween('date',[$firstday,$lastDay]);
            $meetingCount = $meetingCount->whereBetween('date',[$firstday,$lastDay]);
            $documentationCount = $documentationCount->whereBetween('date',[$firstday,$lastDay]);

            $demoCompleted = $demoCompleted->whereBetween('date',[$firstday,$lastDay]);
            $conversionPossible = $conversionPossible->whereBetween('date',[$firstday,$lastDay]);
            $Didntpick = $Didntpick->whereBetween('date',[$firstday,$lastDay]);
            $Fixedappointment = $Fixedappointment->whereBetween('date',[$firstday,$lastDay]);
            $Noneednow = $Noneednow->whereBetween('date',[$firstday,$lastDay]);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereBetween('date',[$firstday,$lastDay]);
            $Softwareprovideravailable = $Softwareprovideravailable->whereBetween('date',[$firstday,$lastDay]);
            $Talkedtojewellery = $Talkedtojewellery->whereBetween('date',[$firstday,$lastDay]);

        }
        if($request->filterText=='This Month')
        {
            Session::put('filterText','This Month');
            $firstday = date('Y-m-01');
            $lastDay = date('Y-m-t');

            $filterText = "This Month";
            $leads = $leads->whereBetween('date',[$firstday,$lastDay]);
            $callsCount = $callsCount->whereBetween('date',[$firstday,$lastDay]);
            $messageCount = $messageCount->whereBetween('date',[$firstday,$lastDay]);
            $mailCount = $mailCount->whereBetween('date',[$firstday,$lastDay]);
            $directVisitCount = $directVisitCount->whereBetween('date',[$firstday,$lastDay]);
            $demoCount = $demoCount->whereBetween('date',[$firstday,$lastDay]);
            $meetingCount = $meetingCount->whereBetween('date',[$firstday,$lastDay]);
            $documentationCount = $documentationCount->whereBetween('date',[$firstday,$lastDay]);

            $demoCompleted = $demoCompleted->whereBetween('date',[$firstday,$lastDay]);
            $conversionPossible = $conversionPossible->whereBetween('date',[$firstday,$lastDay]);
            $Didntpick = $Didntpick->whereBetween('date',[$firstday,$lastDay]);
            $Fixedappointment = $Fixedappointment->whereBetween('date',[$firstday,$lastDay]);
            $Noneednow = $Noneednow->whereBetween('date',[$firstday,$lastDay]);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereBetween('date',[$firstday,$lastDay]);
            $Softwareprovideravailable = $Softwareprovideravailable->whereBetween('date',[$firstday,$lastDay]);
            $Talkedtojewellery = $Talkedtojewellery->whereBetween('date',[$firstday,$lastDay]);

        }
        if($request->filterText=='All Time')
        {

            Session::put('filterText','All Time');
            $leads = $leads;

        }
        if($request->segment!=null && $request->domain!=null)
        {

            Session::put('segment',$request->segment);
             Session::put('domain',$request->domain);
            $leads = $leads->where('segment',$request->segment);
            
            $leads_id = LeadsVer1::orderby('id','desc')->where('segment', $request->segment)->pluck('id');
            
            $callsCount = $callsCount->whereIn('leads_id',$leads_id);
            $messageCount = $messageCount->whereIn('leads_id',$leads_id);
            $mailCount = $mailCount->whereIn('leads_id',$leads_id);
            $directVisitCount = $directVisitCount->whereIn('leads_id',$leads_id);
            $demoCount = $demoCount->whereIn('leads_id',$leads_id);
            $meetingCount = $meetingCount->whereIn('leads_id',$leads_id);
            $documentationCount = $documentationCount->whereIn('leads_id',$leads_id);

            $demoCompleted = $demoCompleted->whereIn('leads_id',$leads_id);
            $conversionPossible = $conversionPossible->whereIn('leads_id',$leads_id);
            $Didntpick = $Didntpick->whereIn('leads_id',$leads_id);
            $Fixedappointment = $Fixedappointment->whereIn('leads_id',$leads_id);
            $Noneednow = $Noneednow->whereIn('leads_id',$leads_id);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereIn('leads_id',$leads_id);
            $Softwareprovideravailable = $Softwareprovideravailable->whereIn('leads_id',$leads_id);
            $Talkedtojewellery = $Talkedtojewellery->whereIn('leads_id',$leads_id);
        }
        if($request->segment==null && $request->domain!=null)
        {

            Session::put('domain',$request->domain);
            $leads = $leads->where('domain',$request->domain);
            $leads_id = LeadsVer1::orderby('id','desc')->where('domain', $request->domain)->pluck('id');
            
            $callsCount = $callsCount->whereIn('leads_id',$leads_id);
            $messageCount = $messageCount->whereIn('leads_id',$leads_id);
            $mailCount = $mailCount->whereIn('leads_id',$leads_id);
            $directVisitCount = $directVisitCount->whereIn('leads_id',$leads_id);
            $demoCount = $demoCount->whereIn('leads_id',$leads_id);
            $meetingCount = $meetingCount->whereIn('leads_id',$leads_id);
            $documentationCount = $documentationCount->whereIn('leads_id',$leads_id);

            $demoCompleted = $demoCompleted->whereIn('leads_id',$leads_id);
            $conversionPossible = $conversionPossible->whereIn('leads_id',$leads_id);
            $Didntpick = $Didntpick->whereIn('leads_id',$leads_id);
            $Fixedappointment = $Fixedappointment->whereIn('leads_id',$leads_id);
            $Noneednow = $Noneednow->whereIn('leads_id',$leads_id);
            $Pickedbutnoresponse = $Pickedbutnoresponse->whereIn('leads_id',$leads_id);
            $Softwareprovideravailable = $Softwareprovideravailable->whereIn('leads_id',$leads_id);
            $Talkedtojewellery = $Talkedtojewellery->whereIn('leads_id',$leads_id);
        }
       
        $leads = $leads->count();
        $callsCount = $callsCount->count();
        $messageCount = $messageCount->count();
        $mailCount = $mailCount->count();
        $directVisitCount = $directVisitCount->count();
        $demoCount = $demoCount->count();
        $meetingCount = $meetingCount->count();
        $documentationCount = $documentationCount->count();

        $demoCompleted = $demoCompleted->count();
        $conversionPossible = $conversionPossible->count();
        $Didntpick = $Didntpick->count();
        $Fixedappointment = $Fixedappointment->count();
        $Noneednow = $Noneednow->count();

        $Pickedbutnoresponse = $Pickedbutnoresponse->count();
        $Softwareprovideravailable = $Softwareprovideravailable->count();
        $Talkedtojewellery = $Talkedtojewellery->count();
        $dashboard = View::make('user.filter',compact('leads','callsCount','messageCount','mailCount','directVisitCount','demoCount','meetingCount','documentationCount','filterText','demoCompleted','conversionPossible','Didntpick','Fixedappointment','Noneednow','Pickedbutnoresponse','Softwareprovideravailable','Talkedtojewellery'))->render();

        return Response::json(['dashboard' => $dashboard,'demoCompleted'=>$demoCompleted,'conversionPossible'=>$conversionPossible,'Didntpick'=>$Didntpick,'Fixedappointment'=>$Fixedappointment,'Noneednow'=>$Noneednow,'Pickedbutnoresponse'=>$Pickedbutnoresponse,'Softwareprovideravailable'=>$Softwareprovideravailable,'Talkedtojewellery'=>$Talkedtojewellery]);

    }
}
