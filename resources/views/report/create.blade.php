@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Add New Task</h1>
      </div>
      <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Task Informations
            </div>
            <div class="panel-body">
               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif
               {!! Form::model($task, [
      'method' => 'POST',
      'url' => ["/Task/store"],
      'class' => 'form-horizontal',
      'id' => 'task',
      'files' => true
   ]) !!}

               <input type="hidden" name="assignedBy" value="{{Auth::user()->id}}">

               <input type="hidden" name="relatedTaskId" value="{{$task->id}}">
               @if(Auth::user()->type == 'admin')
                  <input type="hidden" name="approval" value="{{isset($task->approval) ? $task->approval : 'yes'}}">
               @else

                  <input type="hidden" name="approval" value="{{isset($task->approval) ? $task->approval : 'no'}}">
               @endif

               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('assignedDate') ? 'has-error' : ''}}">
                        {!! Form::label('assignedDate', 'Task Date `*`', ['class' => 'control-label']) !!}
                        {!! Form::date('assignedDate', old(
      'assignedDate',
      Carbon\Carbon::today()->format('Y-m-d')
   ), ['class' => 'form-control date-picker', 'required' => true, 'id' => 'assignedDate']) !!}
                        {!! $errors->first('assignedDate', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('empId') ? 'has-error' : ''}}">
                        {!! Form::label('empId', 'Employee `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('empId', $employees, $employee, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'empId']) !!}
                        {!! $errors->first('empId', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('priority') ? 'has-error' : ''}}">
                        {!! Form::label('priority', 'Priority `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('priority', $priorities, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'priority']) !!}
                        {!! $errors->first('priority', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('projectId') ? 'has-error' : ''}}">
                        {!! Form::label('projectId', 'Project `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('projectId', $projects, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => '  ']) !!}
                        {!! $errors->first('projectId', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('activityId') ? 'has-error' : ''}}">
                        {!! Form::label('activityId', 'Activity `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('activityId', $activities, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'activityId']) !!}
                        {!! $errors->first('activityId', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('instruction') ? 'has-error' : ''}}">
                        {!! Form::label('instruction', 'Instruction `*`', ['class' => 'control-label']) !!}
                        {!! Form::textarea('instruction', null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Give Instruction", 'id' => 'instruction', 'rows' => 2]) !!}
                        {!! $errors->first('instruction', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
               </div>

               <div class="col-md-8 col-md-offset-2">
                  <div class="col-md-4 form-group">
                     <a href="{{Auth::user()->type == 'admin' ? url('/Admin/Task') : url('/Task')}}"
                        class="btn btn-primary">Cancel</a>
                  </div>
                  <div class="col-md-4 form-group">
                     {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Assign', ['class' => 'btn btn-primary']) !!}
                  </div>
                  
               </div>
            </div>
         </div>
      </div>

      {!! Form::close() !!}
   </div>

   <script type="text/javascript">
      $(document).ready(function () {
         startTime();
      });
      function startTime() {
         var today = new Date();
         var h = today.getHours();
         var m = today.getMinutes();
         var s = today.getSeconds();
         m = checkTime(m);
         s = checkTime(s);
         $('#startTime').val(h + ":" + m + ":" + s);
         var t = setTimeout(startTime, 500);
      }
      function checkTime(i) {
         if (i < 10) { i = "0" + i };  // add zero in front of numbers < 10
         return i;
      }
   </script>
@endsection