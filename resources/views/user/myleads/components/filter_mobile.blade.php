 
                          <div class="row">
                            @foreach($leads as $i=>$lead)
                            <?php
                            $FollowUp = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                            
                             $statuses = \App\CfgStatus::where('id',$lead->status)->first();

                          ?> 
<div class="panel panel-default col-md-4" style="margin-right: 20px;margin-left: 20px;">
    <div class="panel-body">
        <h4><b>Name : {{$lead->name}}</b></h4>
       @if($FollowUp!=null)
                                <p>{{$FollowUp->followup_date}}</p>
                           @else
                           <p>{{$lead->followup_date}}</p>
                           @endif
        <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> Mobile No :{{  $lead->mobile_no}}</p>
        <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}">Address: {{  $lead->location}}</p>
          @if($lead->website!=null && $lead->website!="Nil")
                                <p style="color:green;font-weight:bolder">Website : <a href="{{$lead->website}}" target="_blank" style="color:green;font-weight:bolder">Open</a></p>
                            
                            @endif
                            
                             @if($lead->whatsapp!=null && $lead->whatsapp!="Nil")
                                <p style="color:green;font-weight:bolder">Whatsapp : <a href="{{$lead->whatsapp}}" target="_blank" style="color:green;font-weight:bolder">Open</a></p>
                           
                            @endif
                              
                                @if($lead->status=='S')
                                <p style="color: green;font-weight:bold">Status : Initial</p>
                                @elseif($lead->status=='I')
                                 <p style="color: #ff9b00;font-weight:bold">Status : Inprogress</p>
                                  @elseif($lead->status=='N')
                                 <p style="color: red;font-weight:bold">Status : No Need</p>
                                @elseif($lead->status=='P')
                                 <p style="color: orange;font-weight:bold">Status : Pending</p>
                                @else 
                                <p style="color: green;font-weight:bold">Status : Converted</p>
                                @endif
                                  <a href="{{ route('followup.ver2.add', ['id' => $lead->id ]) }}"><span class="badge bg-primary" style="background-color:#337ab7"><i class="fa fa-plus" style="color: white;"></i> Add Followup</span></a>
                                
                                 
                             
                              
     </div>
</div>
 @endforeach

</div>
 {!! $leads->links() !!}   