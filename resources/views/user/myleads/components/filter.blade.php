                        <table class="table table-striped table-bordered table-responsive " id="leads_list d-sm-none">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Date</th>
                            <th>Name</th>
                            
                            <th>Followup By</th>
                            <th>Mobile</th>
                             <th>Location</th>
                            <th>Status</th>
                            <th>Followup Status</th>
                            <th>Followup</th>
                            <th style="width:20px">Remarks</th>
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
                            <td>{{$i + $leads->firstItem()}}</td>
                            @if($FollowUp!=null)
                                <td>{{$lead->next_followup_date}}</td>
                            @else
                                <td>{{$lead->date}}</td>
                            @endif
                            <td>{{$lead->name}}</td>
                           
                           <?php
                           $assigned_to = null;
                            if($FollowUp!=null)
                            {
                                $assigned_to = \App\User::where('type','!=','admin')->where('id',$FollowUp->assigned_to)->first();
                            }
                            
                            ?>

                            @if($assigned_to!=null)
                                <td>{{$assigned_to->name}}</td>
                            @else
                                <td>-</td>
                            @endif
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {{  $lead->mobile_no}}</p>
                            </td>
                             <td>
                               <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}">  {!!    Illuminate\Support\Str::limit($lead->location, 20, ' ...') !!}</p>
                            </td>
                          
                              
                            @if($FollowUp!=null)
                                
                                @if($FollowUp->status=='6')
                                    <td style="color: red;font-weight:bold">No Need</td>
                                @elseif($FollowUp->status=='23')
                                    <td style="color: green;font-weight:bold">Converted</td>
                                @elseif($FollowUp->status=='18')
                                    <td style="color: green;font-weight:bold">Prospect</td>
                                @elseif($FollowUp->status=='13')
                                    <td style="color: green;font-weight:bold">Prospect</td>
                                @elseif($FollowUp->status=='2')
                                    <td style="color: green;font-weight:bold">Prospect</td>
                                @else 
                                    <td style="color: orange;font-weight:bold">Inprogress</td>
                                @endif
                                
                            @else
                                <td style="color: green;font-weight:bold">Initial</td>       
                            @endif
                             @if($FollowUp!=null)
                            <?php
                             $Followupstatuses = \App\CfgStatus::where('id',$FollowUp->status)->first();
                            
                            ?>
                                @if($Followupstatuses!=null)
                                    @if($FollowUp->status=="17")
                                        <td style="color:#4549b1;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="18")
                                        <td style="color:#b45f44;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="6")
                                        <td style="color:#ff0f00;font-weight:bold">{{$Followupstatuses->name}}</td>
                                    @elseif($FollowUp->status=="19")
                                        <td style="color:#424e13;font-weight:bold">{{$Followupstatuses->name}}</td>
                                     @elseif($FollowUp->status=="20")
                                        <td style="color:#68bdc6;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                     @elseif($FollowUp->status=="21")
                                        <td style="color:#980f2f;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="22")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="23")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="24")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        @elseif($FollowUp->status=="25")
                                        <td style="color:#9f2947;font-weight:bold">{{$Followupstatuses->name}}</td>
                                        
                                        
                                        
                                         @else
                                          <td style="color: orange;font-weight:bold">{{$Followupstatuses->name}}</td>
                                      
                                        @endif
                                      @else
                                          <td style="color: orange;font-weight:bold"></td>
                                    @endif
                                
                            @endif
                             
                            @if($FollowUp!=null)
                                <td>  <span class="badge bg-success" style="background-color:#1da1f2">{{$FollowUpCount}}</span></td>
                            @else
                                <td> - </td>
                            @endif
                             
                              
                            @if($FollowUp!=null)
                                <td>{{$FollowUp->remarks}}</td>
                            @else
                                <td>{{$lead->remarks}}</td>
                            @endif
                            
                            <td>
                            
                                <a href="{{ route('followup.ver2.add', ['id' => $lead->id ]) }}"><span class="badge bg-primary" style="background-color:#337ab7"><i class="fa fa-plus" style="color: white;"></i></span></a>
                                
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
