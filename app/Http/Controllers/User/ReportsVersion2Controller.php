<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Leads;
use Illuminate\Support\Arr;
use App\FollowUps;
use App\Charts\LeadsChart;
use DB;
use App\cfgCategory;
use App\CfgStatus;
use App\LeadsVer1;
use Session;
use App\ChartView;
use Carbon\Carbon;
use Response;


class ReportsVersion2Controller extends Controller
{

    public function areas(Request $request)
    {
        $leads = Leads::select('*', \DB::raw('count(*) as total'));
         if($request->category!=null)
        {
            $leads = $leads->where('category',$request->category);
        }
        if($request->district!=null)
        {
            $leads = $leads->where('district',$request->district);
        }
         if($request->area!=null)
        {
            $leads = $leads->where('area','like','%'.$request->area.'%');
        }
         if($request->search!=null)
        {
            $leads = $leads->where('area','like','%'.$request->search.'%');
        }
        
        if($request->area!=null)
        {
             $leads = $leads->groupBy('district')->orderby('total','desc')->paginate(10);
        }
       else
       {

            $leads = $leads->groupBy('area')->orderby('total','desc')->paginate(10);
         
       }

       
        
        $categories = cfgCategory::where('status','Y')->get();
        
        return view('user.reportsver2.areas',compact('leads', 'categories' ));
    }

    public function loadDistricts(Request $request)
    {
       $leads = Leads::select('district', \DB::raw('count(*) as total'))->groupby('district')->where('category',$request->category)->get();

         return Response::json($leads);
     }

    public function loadAreas(Request $request)
    {
         $leads = Leads::select('area', \DB::raw('count(*) as total'))->groupby('area')->where('category',$request->category)->where('district',$request->district)->get();

         return Response::json($leads);
    }


    public function category()
    {
        $leads = Leads::select('category', \DB::raw('count(*) as total'))->groupby('category')->orderby('total','desc')->paginate(10);


         return view('user.reportsver2.category',compact('leads'));
    }

    public function month()
    {

        $leads = DB::table('leads')
            ->select(DB::raw('MONTH(latest_call_date) as month ,count(*) as total'))
            ->orderBy('latest_call_date')
            ->groupBy(DB::raw('MONTH(latest_call_date)'))
            ->paginate(10);
         
        return view('user.reportsver2.month',compact('leads'));
    }
    
    public function prospects(Request $request)
    {
        $followId = Followups::whereIn('status',[2,13,18])->pluck('leads_id');
        
         $leads = Leads::whereIn('id',$followId)->orderby('id','desc')->paginate(30);
        return view('user.reportsver2.prospect_list',compact('leads'));

    }

    public function filter(Request $request)
    { 
        /*$leads_1 = Leads::select('*',DB::raw('count(*) as total'))->groupby('mobile_no')->groupby('location')->having('total','>=',2)->get();

        foreach($leads_1 as $ld1)
        {
            $leads_d = Leads::where('mobile_no',$ld1->mobile_no)->where('location',$ld1->location)->pluck('id');
 
             $leads_d1 = Leads::where('mobile_no',$ld1->mobile_no)->where('location',$ld1->location)->take(count($leads_d)-1)->pluck('id');

             $leads_dd = Leads::whereIn('id', $leads_d1)->delete();
         } 
        
        $leads_1 = Leads::orderby('id','desc')->get();
        foreach($leads_1 as $ld1)
        {
            $followup1 = FollowUps::where('mobile_no',$ld1->mobile_no)->where('address',$ld1->location)->get();
            
            if(count($followup1)>0)
            {
                 $ld1->followup_date = $followup1->last()->followup_date;
                $ld1->save();
            }
            else
            {
                $ld1->followup_date = $ld1->date;
                $ld1->save();
            }
        } 
        $followups = FollowUps::select('*',DB::raw('count(*) as total'))->groupby('leads_id')->get();
         foreach($followups as $ld1)
        {
            $followup1 = FollowUps::where('leads_id',$ld1->leads_id)->orderby('id','desc')->first();
            if($followup1!=null)
            {
                $lead_status = CfgStatus::getLeadStatus($followup1->status,$ld1->leads_id);

                $leads = Leads::where('id',$ld1->leads_id)->first();
                 if($leads!=null)
                {
                    $leads->next_followup_date = $followup1->next_followup_date;
                    $leads->latest_followup_status = $followup1->status;
                    $leads->latest_call_date = $followup1->call_date;
                    $leads->status =$lead_status;
                    $leads->save();
                }
                
            }
            
        } */
 
       

        $leads = Leads::orderby('id','desc');

        $chart = null;
        
        $followup_chart = null;
        $status_chart = null;
        $status = '';
         $chart2 = new LeadsChart;
        $dataset_count = Leads::orderby('id',  'desc');
        $status_chart_count = Leads::orderby('id',  'desc');
        $leads_status = Leads::orderby('id','desc');
        $leads_statusIds = Leads::orderby('id','desc');

        if($request->search!=null)
        {

            $leads = $leads->orwhere('name','like',"%{$request->search}%")
            ->orwhere('area','like',"%{$request->search}%")
            ->orwhere('location','like',"%{$request->search}%")
            ->orwhere('mobile_no','like',"%{$request->search}%");
        }
        if($request->month!=null)
        {
 
            $leads = $leads->whereMonth('latest_call_date', $request->month);
            $leads_status = $leads_status->whereMonth('latest_call_date', $request->month);
            $leads_statusIds = $leads_statusIds->whereMonth('latest_call_date',  $request->month);
        }
        
 
        if($request->category!=null)
        {
            $leads = $leads->Where('category',  $request->category);
            $leads_status = $leads_status->Where('category',  $request->category);
            $leads_statusIds = $leads_statusIds->Where('category',  $request->category);
            $dataset_count = $dataset_count->Where('category',  $request->category);
            $status_chart_count = $status_chart_count->Where('category',  $request->category);
            $category = cfgCategory::where('id',$request->category)->value('category');
            $status .= '-';
            $status .=$category;
        }

        if($request->area!=null)
        {
            $leads = $leads->Where('area',  $request->area);
            $leads_status = $leads_status->Where('area',  $request->area);
            $leads_statusIds = $leads_statusIds->Where('area',  $request->area);
            $dataset_count = $dataset_count->Where('area',  $request->area);
            $status_chart_count = $status_chart_count->Where('area',  $request->area);
            $status .= '-';
            $status .=$request->area;
        }
         if($request->district!=null)
        {
            $leads = $leads->Where('district',  $request->district);
            $leads_status = $leads_status->Where('district',  $request->district);
            $leads_statusIds = $leads_statusIds->Where('district',  $request->district);
            $dataset_count = $dataset_count->Where('district',  $request->district);
            $status_chart_count = $status_chart_count->Where('district',  $request->district);
            $status .= '-';
            $status .=$request->district;
        }
        if($request->status!=null)
        {
            $leads = $leads->WhereIn('latest_followup_status',  $request->status);
            $leads_status = $leads_status->WhereIn('latest_followup_status',  $request->status);
            $leads_statusIds = $leads_statusIds->WhereIn('latest_followup_status',  $request->status);
             $status_chart_count = $status_chart_count->WhereIn('latest_followup_status',  $request->status);
          }
        $status_array = [];
        $startDate = '';
         $rangArray = [];

        $datas = [];
        $status_datas = [];
        
       

        if($request->from_call_date!=null && $request->from_to_date!=null)
        {
            
            
          
            $start_date = $request->from_call_date;
            $end_date =  $request->from_to_date;
            
             
            $leads = $leads->whereBetween('latest_call_date',[$start_date,$end_date]);
            $leads_status = $leads_status->whereBetween('latest_call_date',[$start_date,$end_date]);
            $leads_statusIds = $leads_statusIds->whereBetween('latest_call_date',[$start_date,$end_date]);
            $startDate = strtotime($start_date);
            $endDate = strtotime($end_date);

            for ($currentDate = $startDate; $currentDate <= $endDate; $currentDate += (86400)) {
                $date = date('Y-m-d', $currentDate);
                 if($date!= $start_date)
                {
                    array_pop ($dataset_count->getQuery()->bindings['where']);
                    array_pop ($dataset_count->getQuery()->wheres);

                  
                }
                $datasets = $dataset_count->where('latest_call_date',  $date)->count();
                 array_push($datas,$datasets);
                         
                $rangArray[] = $date;
            }
        }

 
        $leadsId = [];
        $followups_array_status =array();
        if($request->status==null)
        {  
            
            $leads_statuses = $leads_status->groupby('latest_followup_status')->where('latest_followup_status','!=',null)->pluck('latest_followup_status');
            $leads_statusIds = $leads_statusIds->pluck('id');

             array_push($followups_array_status,$leads_statuses);

            foreach($leads_statuses as $leadsFollowUp)
            {   
                $status_data_count  = 0;    
                $leadsId1 =array();
                $followups = Leads::whereIn('id',$leads_statusIds)->where('latest_followup_status',$leadsFollowUp)->count();
                         
                array_push($status_datas,$followups);                       
            }
            
        }
        
         if($request->status!=null)
        {  
            
            $leads_statuses = $leads_status->groupby('latest_followup_status')->where('latest_followup_status','!=',null)->pluck('latest_followup_status');
            $leads_statusIds = $leads_statusIds->pluck('id');

             array_push($followups_array_status,$leads_statuses);

            foreach($leads_statuses as $leadsFollowUp)
            {   
                $status_data_count  = 0;    
                $leadsId1 =array();
                $followups = Leads::whereIn('id',$leads_statusIds)->where('latest_followup_status',$leadsFollowUp)->count();
                         
                array_push($status_datas,$followups);                       
            }
            
        }
        
        $status_datas = array_values( array_filter($status_datas) );
        //dd($followups_array_status);
         if($request->status==null && count($followups_array_status[0])>0)
        {

            
            foreach($followups_array_status[0] as $stas)
            {
               $status_1 = CfgStatus::where('id',$stas)->first();
               if($status_1!=null)
               {
                   $status_array[] = $status_1->name;
               }
                 
    
            }
        }
        if($request->status==null && count($followups_array_status[0])==0)
        {
            $status_1 = CfgStatus::where('active','Y')->get();
        
            foreach($status_1 as $stas)
            {
                        
                $status_array[] = $stas->name;
    
            }
        }
        if($request->status!=null)
        {
            $status_1 = CfgStatus::where('active','Y')->whereIn('id',$request->status)->get();
        
            foreach($status_1 as $stas)
            {
                        
                $status_array[] = $stas->name;
    
            }   
        }
         
         if(count($rangArray)!=0)
        {
            $chart = new LeadsChart;
            $chart->labels( $rangArray);
            $chart->dataset('Dates', 'bar',  $datas )->options([
            'backgroundColor' => [ 'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(201, 203, 207, 0.2)'  
                                ],
            'borderColor' =>    [
                                  'rgb(255, 99, 132)',
                                  'rgb(255, 159, 64)',
                                  'rgb(255, 205, 86)',
                                  'rgb(75, 192, 192)',
                                  'rgb(54, 162, 235)',
                                  'rgb(153, 102, 255)',
                                  'rgb(201, 203, 207)'
                                ],
            'borderWidth'=> 1
                        ]); 
        }

         if(count($status_array)!=0)
        {
            $status_chart = new LeadsChart;
            $status_chart->labels( $status_array);
            $status_chart->dataset('Status ', 'bar',  $status_datas )->options([
            'backgroundColor' => [ 'rgba(255, 99, 132, 0.2)',
                                    'rgba(255, 159, 64, 0.2)',
                                    'rgba(255, 205, 86, 0.2)',
                                    'rgba(75, 192, 192, 0.2)',
                                    'rgba(54, 162, 235, 0.2)',
                                    'rgba(153, 102, 255, 0.2)',
                                    'rgba(201, 203, 207, 0.2)'  
                                ],
            'borderColor' =>    [
                                  'rgb(255, 99, 132)',
                                  'rgb(255, 159, 64)',
                                  'rgb(255, 205, 86)',
                                  'rgb(75, 192, 192)',
                                  'rgb(54, 162, 235)',
                                  'rgb(153, 102, 255)',
                                  'rgb(201, 203, 207)'
                                ],
            'borderWidth'=> 1,
             'responsive'=> true,
    'maintainAspectRatio'=> false,
        'width'=>'1000px',
        'height'=>'1000px'
                        ]); 
        }
       // dd($status_chart);
              // $leads->orderby('sid','desc')->get();
                $leads_filter = Leads::orderby('id','desc')->get();
                $leads = $leads->paginate(50);
                 $areas =  Leads::select('area', \DB::raw('count(*) as total'));
                if($request->category!=null)
                {
                  $areas = Leads::select('area', \DB::raw('count(*) as total'))->where('category',$request->category);  
                }
                if($request->month!=null)
                {
                  $areas = Leads::select('area', \DB::raw('count(*) as total'))->whereMonth('latest_call_date',$request->month);  
                }
                $areas = $areas->groupby('area')->orderby('total','desc')->get();
                
                $categories = cfgCategory::where('status','Y')->get();
                return view('user.reportsver2.list',compact('chart' ,'leads','leads_filter','categories','areas','chart2','status_chart'));
    }

    public function search()
    {
        return view('user.bulk_search.search');
    }
    public function availableleads(Request $request)
    {
        $data  = Session::get('leads_available');
         
        return view('user.bulk_search.available_leads',compact('data'));
    }
    public function importcsv(Request $request)
    {


        $extension = $request->file('csv_file')->extension();
      

        $file = $request->file('csv_file');
        
        $datas = [];
        $cols = [];
        $validate = [];
        if($file){
           
            $datas = $this->csvToArray($file);
            $leads_available =[];
            for ($i = 0; $i < count($datas); $i ++)
            {
                $data = array_map('trim', $datas[$i]);
                //dd($data['mobile_no']); 
                $lead_exit = Leads::where('mobile_no',$data['mobile_no'])->count();
                $lead_ver1_exit = LeadsVer1::where('mobile_no',$data['mobile_no'])->count();
                if($lead_exit==0 && $lead_ver1_exit==0)
                {
                    array_push($leads_available, array('name' => $data['name'],'mobile_no' => $data['mobile_no'],'category' => $data['category'],'date' => $data['date'],'approach_status' => $data['approach_status'],'area' => $data['area'],'district' => $data['district'],'location' => $data['location'],'status' => $data['status'], 'available_status' => 'N'));
                }
                else
                {
                    array_push($leads_available, array('name' => $data['name'],'mobile_no' => $data['mobile_no'],'category' => $data['category'],'date' => $data['date'],'approach_status' => $data['approach_status'],'area' => $data['area'],'district' => $data['district'],'location' => $data['location'],'status' => $data['status'],'available_status' => 'Y'));
                }
                
            }
            
        }
        Session::put('leads_available',$leads_available);
        return redirect()->route('leads.availableleads')->with(['data' => $leads_available]);
     }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        $columns = array("date","name","category","approach_status","mobile_no","district","area","location","status" );
         if (!file_exists($filename) || !is_readable($filename))
            return false;
            $header = null;
            $data = array();
            if (($handle = fopen($filename, 'r')) !== false)
            {
                while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
                {

                    if (!$header)
                    {
                        $headerData = array_map('trim', $row );
                        $result = array_diff($columns, $headerData);
                        if(count($result)==0)
                        {
                            $header =  $row ;
                         }
                        else
                        {
                            return [];
                        }
                        
                    }
                    else
                    {
                         $data[] = array_combine($header, $row);
                    }
                }
                fclose($handle);
            }

        return $data;
    }

    public function uploadavailableleads(Request $request)
    {
          foreach($request->name as $i=>$data)
        {
             
            if($request->available_status[$i]=="N")
            {
                $new = new Leads();
                $new->name = $request->name[$i];
                $new->mobile_no = $request->mobile_no[$i];
                $new->location = $request->location[$i];
                $new->area = $request->area[$i];
                $new->approach_status = $request->approach_status[$i];
                $new->district = $request->district[$i];
                $new->status = $request->status[$i];
                $new->category = $request->category[$i];
                $new->date = $request->date[$i];
                 $new->save();
            }
            
        }
        return redirect(route('leads.ver2.list'))->with('success', 'Lead added successfully');
    }
}
