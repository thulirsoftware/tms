@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Leave Request</h1>
      </div>
      <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Leave Informations
            </div>
            <div class="panel-body">
               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif
               {!! Form::model($leave, [
      'method' => 'POST',
      'url' => ["/Leave/store"],
      'class' => 'form-horizontal',
      'id' => 'leave',
      'files' => true
   ]) !!}
               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('requestDate') ? 'has-error' : ''}}">
                        {!! Form::label('requestDate', 'Request Date `*`', ['class' => 'control-label']) !!}
                        {!! Form::date('requestDate', old(
      'requestDate',
      Carbon\Carbon::today()->format('Y-m-d')
   ), ['class' => 'form-control date-picker', 'required' => true, 'id' => 'requestDate', 'readonly' => true]) !!}
                        {!! $errors->first('requestDate', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('leaveTypeId') ? 'has-error' : ''}}">
                        {!! Form::label('leaveTypeId', 'Leave Type `*`', ['class' => 'control-label']) !!}
                        {!! Form::select('leaveTypeId', $leaveTypes, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'leaveTypeId']) !!}
                        {!! $errors->first('leaveTypeId', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <h4>Available Leave:</h4>
                     <h3><input class="form-control" type="text" name="availLeaveDays" id="availLeaveDays" readonly=""
                           value="{{$leave->availLeaveDays}}"></h3>
                  </div>
               </div>
               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('leaveFromDate') ? 'has-error' : ''}}">
                        {!! Form::label('leaveFromDate', 'From Date `*`', ['class' => 'control-label']) !!}
                        {!! Form::date('leaveFromDate', old(
      'leaveFromDate',
      Carbon\Carbon::today()->format('Y-m-d')
   ), ['class' => 'form-control', 'required' => true]) !!}
                        {!! $errors->first('leaveFromDate', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('leaveToDate') ? 'has-error' : ''}}">
                        {!! Form::label('leaveToDate', 'To Date `*`', ['class' => 'control-label']) !!}
                        {!! Form::date('leaveToDate', null, ['class' => 'form-control', 'required' => true]) !!}
                        {!! $errors->first('leaveToDate', '
                           <p class="help-block">:message</p>
                           ') !!}
                     </div>
                  </div>
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <h4>No.of Leave Days:</h4>
                     <h3><input class="form-control" type="text" name="totalLeaveDays" id="totalLeaveDays" readonly=""
                           value="{{old('totalLeaveDays')}}"></h3>
                  </div>
               </div>

               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('reason') ? 'has-error' : ''}}">
                        {!! Form::label('reason', 'Reason For Leave `*`', ['class' => 'control-label']) !!}
                        {!! Form::textarea('reason', null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Give Your Valuable Reason", 'id' => 'reason', 'rows' => 2]) !!}
                        {!! $errors->first('reason', '
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
                  <div class="col-md-1"></div>
                  <div class="col-md-3">
                     <h4>No.of Leave Taken:</h4>
                     <h3><input class="form-control" type="text" name="takenLeaveDays" id="totalLeaveDays" readonly=""
                           value="{{old('takenLeaveDays')}}"></h3>
                  </div>
               </div>

               <div class="col-md-12 col-md-offset-2">
                  <div class="col-md-6 form-group">
                     <a href="{{Auth::user()->type == 'admin' ? url('/Admin/Leave') : url('/Leave')}}"
                        class="btn btn-primary">Cancel</a>
                  </div>
                  <div class="col-md-6 form-group">
                     {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Submit', ['class' => 'btn btn-primary']) !!}
                  </div>
                  
               </div>
            </div>
         </div>
      </div>

      {!! Form::close() !!}
   </div>

   <script type="text/javascript">
      $(function () {
         totalLeaveDays();
         $(document).change(totalLeaveDays);
      });
      // function startTime() {
      //     var today = new Date();
      //     var h = today.getHours();
      //     var m = today.getMinutes();
      //     var s = today.getSeconds();
      //     m = checkTime(m);
      //     s = checkTime(s);
      //     $('#startTime').val(h + ":" + m + ":" + s);
      //     var t = setTimeout(startTime, 500);
      // }
      // function checkTime(i) {
      //     if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
      //     return i;
      // }
      function totalLeaveDays() {

         // end - start returns difference in milliseconds 
         var startDate = new Date($('#leaveFromDate').val());
         var endDate = new Date($('#leaveToDate').val());

         var diff_date = endDate - startDate;

         var years = Math.floor(diff_date / 31536000000);
         var months = Math.floor((diff_date % 31536000000) / 2628000000);
         var days = Math.floor(((diff_date % 31536000000) % 2628000000) / 86400000);
         $('#totalLeaveDays').val(days);
      }
   </script>
@endsection