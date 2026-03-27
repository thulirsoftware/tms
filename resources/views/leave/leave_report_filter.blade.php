
    @foreach($leave as $leave)
    <?php 
        $name = App\Employee::where('id',$leave->empId)->first();
        //dd($leave->empId);
        $leavetype = App\CfgLeaveType::where('id',$leave->leaveTypeId)->first();
    ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{$leave->requestDate}}</td>
            @if($name!=null)
            <td>{{ $name['name'] }}</td>
            @else
            <td></td>
            @endif
            <td>{{ $leavetype['name'] }}</td>
            <td>{{$leave->leaveFromDate}}</td>
            <td>{{$leave->leaveToDate}}</td>
            <td>{{$leave->totalLeaveDays}}</td>
             <td><span
                                            class="right badge @if( $leave->approval=='' ) badge-danger @elseif( $leave->approval=='no' ) badge-warning   @else badge-success @endif">@if( $leave->approval=='') Not Approved @elseif($leave->approval=='no' ) Rejected @else Approve  @endif</span>
             </td>
                 <td>
                                        <select name="status"  class="form-control"
                                            onChange="changeStatus( {{$leave->id}},{{$leave->empId}},this.value)">
                                            <option @if($leave->approval == '') selected @endif
                                                value="">Select </option>
                                                  <option @if($leave->approval == 'yes') selected @endif
                                                value="yes">Approved</option>
                                            <option @if($leave->approval == 'no') selected @endif
                                                value="no">Reject</option>
                                               
                                          
                                        </select>
            </td>                   
          
        </tr>
    @endforeach