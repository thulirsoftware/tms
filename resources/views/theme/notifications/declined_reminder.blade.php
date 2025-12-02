 
@if(Auth::user()->type=='admin')
<a href="{{url('/Admin/Leave')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i>

@else
<a href="{{url('/Leaverequest')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i>

@endif
@if($notification->data['message'] == 'Leave declined')
<p>{{$notification->data['message']}} : from  
{{$notification->data['leave']['leaveFromDate']}} <br> to  {{$notification->data['leave']['leaveToDate']}} .
 </p>
             <p style="float: right;"> <?=time_since($notification->data['leave']['updated_at'])?> ago</p>
          
</a>
    @endif
@if($notification->data['message'] == 'Permission declined')
<p>{{$notification->data['message']}} : on  
{{$notification->data['leave']['permissionDate']}}  <br> -  {{$notification->data['leave']['permissionDate']}} from  {{$notification->data['leave']['fromTime']}} to <br>{{$notification->data['leave']['toTime']}} .
 </p>
             <p style="float: right;"> <?=time_since($notification->data['leave']['updated_at'])?> ago</p>
          
</a>
    @endif