 

<p>{{$notification->data['message']}} : {{$notification->data['fromUser']['name']}} has <br> requested leave from  
{{$notification->data['leave']['leaveFromDate']}} <br> to  {{$notification->data['leave']['leaveToDate']}} .
<span class="text-danger  " style="font-weight: bold;">Action required</span>.</p>
             <p style="float: right;"> <?=time_since($notification->data['leave']['updated_at'])?> ago</p>
          
</a>