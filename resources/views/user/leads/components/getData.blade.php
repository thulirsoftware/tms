                        @foreach($leads as $i=>$lead)
                            <?php
                            $FollowUp = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                            $statuses = \App\CfgStatus::where('id',$lead->status)->first();
                          ?>
                          <tr>
                            <td>{{$i+1}}</td>
                            <td>{{$lead->name}}</td>
                            <td>{{$lead->created_at->toDateString()}}</td>
                           
                           <td>
                             <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {{(strlen($lead->mobile_no) > 20) ? substr($lead->mobile_no,0,10).' ...' : $lead->mobile_no}}</p>
                            </td>
                            <td>{{$lead->area}}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {{(strlen($lead->location) > 20) ? substr($lead->location,0,16).' ...' : $lead->location}}</p></td>
                            @if($lead->website!=null && $lead->website!="Nil")
                                <td style="color:green;font-weight:bolder"><a href="{{$lead->website}}" target="_blank" style="color:green;font-weight:bolder">Open</a></td>
                            @elseif($lead->website=="Nil")
                                <td style="color:red;font-weight:bolder">N.A</td>
                            @else
                                <td style="color:red;font-weight:bolder">N.A</td>
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

                           
                           
                          </tr>
                          @endforeach
                          
                         
                         