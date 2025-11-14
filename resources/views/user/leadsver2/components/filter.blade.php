                        <table class="table table-striped table-bordered table-responsive " id="leads_list d-sm-none">
                        <thead>
                          <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Date</th>
                            <th>Mobile</th>
                             <th>Location</th>
                             <th>Status</th>
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
                            <td>{{$lead->name}}</td>
                            
                            <td>{{$lead->latest_call_date}}</td>
                            
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->mobile_no}}"> {{  $lead->mobile_no}}</p>
                            </td>
                             <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$lead->location}}"> {{  $lead->location}}</p>
                            </td>
                                
                                
                                
                                @if($lead->status=='S')
                                <td style="color: green;font-weight:bold">Initial</td>
                                @elseif($lead->status=='I')
                                 <td style="color: #ff9b00;font-weight:bold">Inprogress</td>
                                  @elseif($lead->status=='N')
                                 <td style="color: red;font-weight:bold">No Need</td>
                                @elseif($lead->status=='P')
                                 <td style="color: orange;font-weight:bold">Pending</td>
                                @else 
                                <td style="color: green;font-weight:bold">Converted</td>
                                @endif
                                
                                @if($FollowUp!=null)
                                    <td>{{$FollowUp->remarks}}</td>
                                @else
                                    <td>{{$lead->remarks}}</td>
                                @endif
                            <td>
                                <a href="{{ route('leads.ver2.edit', ['id' => $lead->id ]) }}"><span class="badge bg-info" style="background-color:#17a2b8"><i class="fa fa-edit" style="color: white;"></i></span>
                                </a> 
                                <a href="{{ route('followup.ver2.add', ['id' => $lead->id ]) }}"><span class="badge bg-primary" style="background-color:#337ab7"><i class="fa fa-plus" style="color: white;"></i></span></a>
                                <a class="Deleteconfirmation" href="{{ route('leads.ver2.delete', ['id' => $lead->id ]) }}"><span class="badge bg-danger" style="background-color:#dc3545"><i class="fa fa-trash" style="color: white;"></i></span></a>
                            </td>
                          </tr>
                          @endforeach
                          </tbody>
                      </table>
                     
{{ $leads->appends($_GET)->links() }}
<script type="text/javascript">
    var elems = document.getElementsByClassName('Deleteconfirmation');
    var confirmIt2 = function (e) {
        if (!confirm('Are you sure  you want to delete lead?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt2, false);
    }
</script>
