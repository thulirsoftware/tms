             @if(count($leads)>0)           
                        <table class="table table-striped  sampleTable" id="leads_list">
                            <thead>
                                <tr>
                                    <th><b>S.No</b></th>
                                    <th>Date</th>
                                    <th><b>Name</b></th>
                                     <th><b>Mobile / Email</b></th>
                                    <th><b>Area</b></th>
                                    <th><b>District</b></th>
                                    <th><b>Location</b></th>
                                     <th><b>Followup Status</b></th>
                                    <th><b>Followup</b></th>
                                    <th><b>Add Followup</b></th>
                                </tr>
                            </thead>
                        <tbody style="margin-top: 30px; " class="table-bordered">
                          <?php $i=0;?>
                         @foreach($leads as  $lead)
                          @if($lead->latest_followup_status!=6)
                            <?php
                            
                            $FollowUp = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->first();
                             $FollowUpCount = \App\FollowUps::where('leads_id',$lead->id)->orderby('id','desc')->count();
                            
                             $statuses = \App\CfgStatus::where('id',$lead->status)->first();

                          ?>
                          <tr>
                            <td>{{$i + $leads->firstItem()}}</td>
                            <td>{{$lead->latest_call_date}}</td>
                            
                            <td>{!! $lead->name !!}</td>
                            
                            <td>
                             <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {!!  $lead->mobile_no !!}</p>
                            </td>
                            <td>{!!  $lead->area !!}</td>
                            <td>{!!  $lead->district !!}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {!!    Illuminate\Support\Str::limit($lead->location, 20, ' ...') !!}</p></td>
                            
                            
                             
                            
                              
                             @if($FollowUp!=null )
                            <?php
                            

                                $Followupstatuses = \App\CfgStatus::where('id',$lead->latest_followup_status)->first();
                             
                            
                            
                            
                            ?>
                                @if($Followupstatuses!=null)
                                    @if($FollowUp->status=="18")
                                        <td style="color:#b45f44;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="13")
                                        <td style="color:#ff0f00;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="2")
                                        <td style="color:#424e13;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                        
                                        
                                         @else
                                          <td style="color: green;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                        @endif
                                      @else
                                          <td style="color: orange;font-weight:bold"></td>
                                    @endif
                            @endif
                             
                              @if($FollowUp!=null)
                            <td>  <span class="badge bg-success" style="background-color:#1da1f2">{{$FollowUpCount}}</span></td>
                            @else
                            <td></td>
                            @endif
                             <td> <a href="{{ route('followup.ver2.add', ['id' => $lead->id ]) }}"><span class="badge bg-primary" style="background-color:#337ab7"><i class="fa fa-plus" style="color: white;"></i></span></a></td>
                          </tr>
                          <?php $i=$i+1;?>
                          @endif
                          
                          @endforeach
                          </tbody>
                      </table>
 
{{ $leads->appends($_GET)->links() }}

@else

<h4 style="text-align:center;">We couldn't find any leads that match your search criteria.</h4>
@endif