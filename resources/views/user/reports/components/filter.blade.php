             @if(count($leads)>0)           
                        <table class="table table-striped  sampleTable" id="leads_list">
                      
                        <tbody style="margin-top: 30px;display: block;" class="table-bordered">
                          
                         @foreach($leads as $i=>$lead)
                         @if($i%19==0)
                             <tr>
                            <td><b>S.No</b></td>
                            <td><b>Name</b></td>
                             <td><b>Mobile / Email</b></td>
                            <td><b>Area</b></td>
                            <td><b>Location</b></td>
                            <td><b>Website</b></td>
                            <td><b>Whatsapp</b></td>
                            <td><b>Status</b></td>
                             <td><b>Remarks</b></td>
                            <td><b>Followup</b></td>
                           </tr>
                           @endif
                            <?php
                            $FollowUp = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                            
                             $statuses = \App\CfgStatus::where('id',$lead->status)->first();

                          ?>
                          <tr>
                            <td>{{$i+1}}</td>
                            <td>{!! $lead->name !!}</td>
                            
                            <td>
                             <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {!!  $lead->mobile_no !!}</p>
                            </td>
                            <td>{!!  $lead->area !!}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {!!    $lead->location !!}</p></td>
                            @if($lead->website!=null && $lead->website!="Nil")
                                <td style="color:green;font-weight:bolder"><a href="{{$lead->website}}" target="_blank" style="color:green;font-weight:bolder">Open</a></td>
                            @elseif($lead->website=="Nil")
                                <td style="color:#1da1f2;font-weight:bolder">N.A</td>
                            @else
                                <td style="color:#1da1f2;font-weight:bolder">N.A</td>
                            @endif
                            
                             @if($lead->whatsapp!=null && $lead->whatsapp!="Nil")
                                <td style="color:green;font-weight:bolder"><a href="{{$lead->whatsapp}}" target="_blank" style="color:green;font-weight:bolder">Open</a></td>
                            @elseif($lead->whatsapp=="Nil")
                                <td style="color:#1da1f2;font-weight:bolder">N.A</td>
                            @else
                                <td style="color:#1da1f2;font-weight:bolder">N.A</td>
                            @endif
                            
                           
                            @if($FollowUp!=null)
                            <?php
                             $Followupstatuses = \App\CfgStatus::where('id',$FollowUp->status)->first();
                            
                            ?>
                                @if($Followupstatuses!=null)
                                    @if($FollowUp->status=="9")
                                        <td style="color:#4549b1;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="4")
                                        <td style="color:#b45f44;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="6")
                                        <td style="color:#ff0f00;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="2")
                                        <td style="color:#424e13;font-weight:bold">{{$Followupstatuses->name}}</td>
                                     @elseif($FollowUp->status=="8")
                                        <td style="color:#68bdc6;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                     @elseif($FollowUp->status=="7")
                                        <td style="color:#980f2f;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="5")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                         @else
                                          <td style="color: orange;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                        @endif
                                      @else
                                          <td style="color: orange;font-weight:bold"></td>
                                    @endif
                            @elseif($lead->status!=null)
                             @if($statuses!=null)
                            <td style="color: #992600;font-weight:bold">{{$statuses->name}}</td>
                            @else
                            <td></td>
                            @endif
                           @else
                           <td style="color: #992600;font-weight:bold">{{$lead->status}}</td>
                            @endif
                             @if($FollowUp!=null)
                             <td>
                                 <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$FollowUp->remarks}}"> {{(strlen($FollowUp->remarks) > 20) ? substr($FollowUp->remarks,0,16).' ...' : $FollowUp->remarks}}</p>
                               </td>
                             @else
                             <td>{{$lead->remarks}}</td>
                             @endif
                            @if($FollowUp!=null)
                            <td>  <span class="badge bg-success" style="background-color:#1da1f2">{{$FollowUpCount}}</span></td>
                            @else
                            <td></td>
                            @endif
                             
                          </tr>
                          @endforeach
                          </tbody>
                      </table>
 
{{ $leads->appends($_GET)->links() }}

@else

<h4 style="text-align:center;">We couldn't find any leads that match your search criteria.</h4>
@endif