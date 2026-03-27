                        <table class="table table-striped table-bordered" id="leads_list_download">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Location</th>
                             <th>Status</th>
                             <th style="width:20px">Remarks</th>
                            <th>Followup</th>
                           </tr>
                        </thead>
                        <tbody>
                        @foreach($leads as $i=>$lead)
                            <?php
                            $FollowUp = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                            
                             $statuses = \App\CfgStatus::where('id',$lead->status)->first();

                          ?>
                          <tr>
                            <td>{{$i+1}}</td>
                            <td>{{$lead->name}}</td>
                             <td>{{$lead->latest_call_date}}</td>
                           
                            <td>
                             <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {{ $lead->mobile_no}}</p>
                            </td>
                            <td>{{$lead->area}}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {{$lead->location}}</p></td>
                          
                         
                           
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
                                 <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$FollowUp->remarks}}"> {{$FollowUp->remarks}}</p>
                               </td>
                             @else
                             <td>{{$lead->remarks}}</td>
                             @endif
                            @if($FollowUp!=null)
                            <td>{{$lead->followup_date}} <span class="badge bg-success" style="background-color:#1da1f2">{{$FollowUpCount}}</span></td>
                            @else
                            <td></td>
                            @endif
                         
                          </tr>
                          @endforeach
                          </tbody>
                      </table>
                      
                       