@extends('theme.default')
@section('content')
<div class="row">
<div class="col-lg-12 well">
      <div class="col-md-4">
         <div class="form-group">
            <label>Config Tables</label>
               <select class="form-control" id="tableFilter" onchange="viewTable()">
               <option value="">Select Table Name</option>
               @foreach($tables as $key => $table)           
               <option value="{{base64_encode($key)}}" <?=isset($tableName)?(($tableName==$key)?'selected':''):''?>>{{$table['model']}}</option>
               @endforeach
            </select>
         </div>
      </div>
    <div class="col-md-4">  
    <br>
  
    </div>
</div>

<div class="col-lg-12 well">
@if(isset($tableName))
    <table class="table table-striped table-bordered table-hover table-condensed ">
    <thead>
    <tr>
        @foreach($tables[$tableName]['columns'] as $key=>$column)
            <th>{{strtoupper($column)}}</th>
        @endforeach
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>

        @foreach($tableRows as $rowKey=>$row)
            <tr>
               @foreach($row as $valueKey => $value)
                <td><input class="form-control" type="text" id="{{$row->id}}_{{$valueKey}}" name="{{$row->id}}_{{$valueKey}}" readonly="" value="{{$value}}"></td>
               @endforeach
                           <td> 
            <a href="{{url('/ConfigTable')}}/{{base64_encode($tableName)}}/{{$row->id}}/edit"><i class="fa fa-edit" style="font-size:20px;color:blue"></i></a>&nbsp;
            <a href="{{url('/ConfigTable')}}/{{base64_encode($tableName)}}/{{$row->id}}/destroy"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
            </td>
            </tr>

        @endforeach

    </tbody>
</table>
@endif
</div>
<script type="text/javascript">
$(document).ready(function(){
 $('[data-toggle="tooltip"]').tooltip();  
 // viewTable();
});
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
function startTime(taskId) {
    var time=getTime();
    
    if($('#currentTasksTable tr').length>1)
    {
            $('.alert-danger').css("display", "none");
            if (!$('#taskComment').val()) {
                  valid = false;
                  $("#taskComment").focus();
                  $('.alert-danger').css("display", "block");
                        // insertAfter('#taskComment');
            }
            if (!$('#taskStatus').val()) {
                  valid = false;
                  $("#taskStatus").focus();
                  $('.alert-danger').css("display", "block");
            }
            return false;
            exit();
    }
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('SetStartTime') }}",
             data: { taskId:taskId,time:time }
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
             }).fail(function(xhr, status, error) {
                // Handle the error (e.g., CSRF token mismatch or other issues)
                if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            }); 
   
}
function stopTime(taskId) {
    var time=getTime();
     var valid = true;
            $('span.error', this).remove();
            if (!$('#taskComment').val()) {
                  valid = false;
                  $("#taskComment").focus();
                  $('<span class="alert-danger" style="text-align:center;" >Please give Comment</span>').
                        insertAfter('#taskComment');
            }
            if (!$('#taskStatus').val()) {
                  valid = false;
                  $("#taskStatus").focus();
                  $('<span class="alert-danger" style="text-align:center;" >Choose status!</span>').
                        insertAfter('#taskStatus');
            }
    var taskComment=$("#taskComment").val();
    var taskStatus=$("#taskStatus").find('option:selected').val();
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('SetEndTime') }}",
             data: { taskId:taskId,time:time,taskStatus:taskStatus,taskComment:taskComment }
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
             }).fail(function(xhr, status, error) {
                // Handle the error (e.g., CSRF token mismatch or other issues)
                if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            }); 
   
}
function approveTask(taskId) {
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('ApproveTask') }}",
             data: { taskId:taskId }
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
             }).fail(function(xhr, status, error) {
                // Handle the error (e.g., CSRF token mismatch or other issues)
                if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            }); 
   
}

function declineTask(taskId) {
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('ApproveTask') }}",
             data: { taskId:taskId }
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
             }).fail(function(xhr, status, error) {
                // Handle the error (e.g., CSRF token mismatch or other issues)
                if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            }); 
   
}

function setInterrupt(interruptFor) {
    var time=getTime();
      $.ajaxSetup({
             headers: {
                  'X-CSRF-TOKEN': '{{csrf_token()}}'
             }
        });
   
        $.ajax({
             method: "POST",
             url: "{{ route('SetInterrupt') }}",
             data: { interruptFor:interruptFor,time:time }
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
             }).fail(function(xhr, status, error) {
                 if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            }); 
   
}

   function viewTable()
   {
      var table = $("#tableFilter option:selected").val();
      if(table!='')
      {
        window.location.href = "{{URL::to('/Admin/ConfigTable')}}/"+table+"/show";
      }else
      {        
        window.location.href = "{{URL::to('/Admin/ConfigTable')}}";  
      }
      
   
   
   }
</script>
@endsection