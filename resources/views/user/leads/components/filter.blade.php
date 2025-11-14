                        <table class="table table-striped table-bordered" id="leads_list">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                            <th>Area</th>
                            <th>Location</th>
                            <th style="width:20px">Website</th>
                            <th style="width:20px">Whatsapp</th>
                            <th>Status</th>
                             <th style="width:20px">Remarks</th>
                            <th>Followup</th>
                            <th>Action</th>
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
                            <td>{{$lead->created_at->toDateString()}}</td>
                           
                            <td>
                             <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {{  $lead->mobile_no}}</p>
                            </td>
                            <td>{{$lead->area}}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {{  $lead->location}}</p></td>
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
                            <td>{{$lead->followup_date}} <span class="badge bg-success" style="background-color:#1da1f2">{{$FollowUpCount}}</span></td>
                            @else
                            <td></td>
                            @endif
                            <td>
                                <a href="{{ route('leads.edit', ['id' => $lead->id ]) }}"><span class="badge bg-info" style="background-color:#17a2b8"><i class="fa fa-edit" style="color: white;"></i></span>
                                </a> 
                           
                                <a href="{{ route('followup.add', ['id' => $lead->id ]) }}"><span class="badge bg-primary" style="background-color:#337ab7"><i class="fa fa-plus" style="color: white;"></i></span></a>
                                
                                 <a class="Deleteconfirmation" href="{{ route('leads.delete', ['id' => $lead->id ]) }}"><span class="badge bg-danger" style="background-color:#dc3545"><i class="fa fa-trash" style="color: white;"></i></span></a>
                            </td>
                          </tr>
                          @endforeach
                          </tbody>
                      </table>
                      
                        {!! $leads->links() !!}   
<script type="text/javascript">
    var elems = document.getElementsByClassName('Deleteconfirmation');
    var confirmIt2 = function (e) {
        if (!confirm('Are you sure  you want to delete lead?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt2, false);
    }
</script>
