
@if(Auth::user()->type=='admin')
<a href="{{url('/Admin/Task')}}/{{$notification->data['task']['empId']}}"><i class="fa fa-paper-plane" aria-hidden="true"></i>

@else
<a href="{{url('/Task')}}"><i class="fa fa-paper-plane" aria-hidden="true"></i>

@endif
            {{$notification->data['fromUser']['name']}} {{$notification->data['message']}} 
            @if(Auth::user()->empId==$notification->data['toUser']['empId']&&Auth::user()->type!='admin')
            	 
            @elseif($notification->data['fromUser']['empId']!=$notification->data['toUser']['empId'])
             to {{$notification->data['toUser']['name']}}            
            @endif
             <p style="float: right;"> <?=time_since($notification->data['task']['updated_at'])?> ago</p>
</a>