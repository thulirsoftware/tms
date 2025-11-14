@extends('theme.default')

@section('content')
<div class="row">
<br>
<div class="col-lg-12 well">
        <h3>Presence / Leave Details
        <div style="float: right;">
        <a href="{{url('/Leave')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded" ><i class="fa fa-plus" aria-hidden="true"></i> Leave Request</a>
        </div>
        </h3>
    <table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>Request Date</th>
            <th>Leave Type</th>
            <th>Reason</th>
            <th>No.of Days</th>
            <th>From</th>
            <th>To</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($leaves as $leave)
        <tr>
            <td>{{$leave->requestDate}}</td>
            <td>{{$leaveTypes[$leave->leaveTypeId]}}</td>
            <td>{{$leave->reason}}</td>
            @if($leave->priority!=null)
            <sup><i class="fa fa-flag {{($leave->priority!=0)?(($leave->priority==1)?('supMedium'):'supLow'):'supHigh'}}"></i></sup>
            @endif
            </td>
            <td>{{$leave->totalLeaveDays}}</td>
            <td>{{$leave->leaveFromDate}}</td>
            <td>{{$leave->leaveToDate}}</td>

            <td> 
            @if(Auth::user()->type!='admin')
                @if($leave->approval=='yes')
            <i id="startTimer<?=$leave->id?>" class="fa fa-thumbs-up" style="font-size:25px;color:green"></i>
            &nbsp;  
                @elseif($leave->approval=='no')
            <i id="approveTask<?=$leave->id?>" class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                @elseif($leave->approval=='')
            <i id="approveTask<?=$leave->id?>" class="fa fa-spinner" style="font-size:25px;color:blue"></i>
                @endif
            @else  
                @if($leave->approval=='no')
            <i id="declineTask<?=$leave->id?>" onclick="approveTask({{$leave->id}})" class="fa fa-thumbs-up" style="font-size:25px;color:green"></i>
                @else
            <i id="approveTask<?=$leave->id?>" onclick="declineTask({{$leave->id}})" class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                @endif
            @endif

            @if(Auth::user()->id==$leave->assignedBy||Auth::user()->type=='admin')
            <a href="{{url('/Task')}}/{{$leave->id}}/edit"><i class="fa fa-edit" style="font-size:20px;color:blue"></i></a>&nbsp;
            <a href="{{url('/Task')}}/{{$leave->id}}/destroy"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
            @endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
</div>

<script type="text/javascript">
// $(document).ready(function(){
// startTime();
// });
$('.clicker').click(function(){

  $(this).nextUntil('.clicker').slideToggle('normal');
});
function getTime() {
    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    m = checkTime(m);
    s = checkTime(s);
    $('#startTime').val(h + ":" + m + ":" + s);
    return h + ":" + m + ":" + s;
    // var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}
function startTime(leaveId) {
    var time=getTime();
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('SetStartTime') }}",
             data: { leaveId:leaveId,time:time }
             }).done(function(data){  
                console.log(data);    
                if(data.status==true)
                {
                    location.reload(true);
                }      
                else
                {
                    alert('Try Again!');
                    location.reload(true);
                } 
             }); 
   
}
function stopTime(leaveId) {
    var time=getTime();
     var valid = true;
            $('span.error', this).remove();
            if (!$('#leaveComment').val()) {
                  valid = false;
                  $("#leaveComment").focus();
                  $('<span class="alert-danger" style="text-align:center;" >Please give Comment</span>').
                        insertAfter('#leaveComment');
            }
            if (!$('#leaveStatus').val()) {
                  valid = false;
                  $("#leaveStatus").focus();
                  $('<span class="alert-danger" style="text-align:center;" >Choose status!</span>').
                        insertAfter('#leaveStatus');
            }
    var leaveComment=$("#leaveComment").val();
    var leaveStatus=$("#leaveStatus").find('option:selected').val();
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('SetEndTime') }}",
             data: { leaveId:leaveId,time:time,leaveStatus:leaveStatus,leaveComment:leaveComment }
             }).done(function(data){  
                console.log(data);    
                if(data.status==true)
                {
                    location.reload(true);
                }      
                else
                {
                    alert('Try Again!');
                    location.reload(true);
                } 
             }); 
   
}
function approveTask(leaveId) {
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('ApproveTask') }}",
             data: { leaveId:leaveId }
             }).done(function(data){  
                console.log(data);    
                if(data.status==true)
                {
                    location.reload(true);
                }      
                else
                {
                    alert('Try Again!');
                    location.reload(true);
                } 
             }); 
   
}

function declineTask(leaveId) {
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('ApproveTask') }}",
             data: { leaveId:leaveId }
             }).done(function(data){  
                console.log(data);    
                if(data.status==true)
                {
                    location.reload(true);
                }      
                else
                {
                    alert('Try Again!');
                    location.reload(true);
                } 
             }); 
   
}
</script>
@endsection