@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Permission Request</h1>
      </div>
   </div>

   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Permission Information
            </div>
            <div class="panel-body">

               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif

               {!! Form::open([
      'method' => 'POST',
      'url' => ["/permission/store"],
      'class' => 'form-horizontal',
      'id' => 'permission',
      'files' => true
   ]) !!}

               <div class="col-md-12">

                  {{-- Request Date --}}
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('requestDate') ? 'has-error' : ''}}">
                        {!! Form::label('requestDate', 'Request Date *', ['class' => 'control-label']) !!}
                        {!! Form::date(
      'requestDate',
      old('requestDate', Carbon\Carbon::today()->format('Y-m-d')),
      ['class' => 'form-control', 'required' => true, 'readonly' => true]
   ) !!}
                        {!! $errors->first('requestDate', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>

                  <div class="col-md-1"></div>

                  {{-- Permission Date --}}
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('permissionDate') ? 'has-error' : ''}}">
                        {!! Form::label('permissionDate', 'Permission Date *', ['class' => 'control-label']) !!}
                        {!! Form::date(
      'permissionDate',
      old('permissionDate'),
      [
         'class' => 'form-control',
         'required' => true,
         'min' => Carbon\Carbon::today()->format('Y-m-d')
      ]
   ) !!}
                        {!! $errors->first('permissionDate', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>

                  <div class="col-md-1"></div>

                  {{-- From Time --}}
                  {{-- From Time --}}
                  <div class="col-md-2">
                     <div class="form-group {{ $errors->has('fromTime') ? 'has-error' : ''}}">
                        {!! Form::label('fromTime', 'From Time *', ['class' => 'control-label']) !!}
                        {!! Form::text('fromTime', old('fromTime'), ['class' => 'form-control', 'required' => true, 'id' => 'fromTime']) !!}
                        {!! $errors->first('fromTime', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>

                  {{-- To Time --}}
                  <div class="col-md-2">
                     <div class="form-group {{ $errors->has('toTime') ? 'has-error' : ''}}">
                        {!! Form::label('toTime', 'To Time *', ['class' => 'control-label']) !!}
                        {!! Form::text('toTime', old('toTime'), ['class' => 'form-control', 'required' => true, 'id' => 'toTime']) !!}
                        {!! $errors->first('toTime', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>

               </div>

               {{-- Total Hours --}}
               <div class="col-md-12">
                  <div class="col-md-3">
                     <div class="form-group {{ $errors->has('totalHours') ? 'has-error' : ''}}">
                        {!! Form::label('totalHours', 'Total Hours *', ['class' => 'control-label']) !!}
                        {!! Form::text(
      'totalHours',
      old('totalHours'),
      ['class' => 'form-control', 'required' => true, 'readonly' => true, 'id' => 'totalHours']
   ) !!}
                        {!! $errors->first('totalHours', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>
               </div>

               {{-- Reason --}}
               <div class="col-md-12">
                  <div class="col-md-6">
                     <div class="form-group {{ $errors->has('reason') ? 'has-error' : ''}}">
                        {!! Form::label('reason', 'Reason *', ['class' => 'control-label']) !!}
                        {!! Form::textarea(
      'reason',
      old('reason'),
      [
         'class' => 'form-control',
         'required' => true,
         'placeholder' => "Enter reason for permission",
         'rows' => 2
      ]
   ) !!}
                        {!! $errors->first('reason', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>
               </div>

               {{-- Submit / Cancel --}}
               <div class="col-md-12 col-md-offset-2">
                  <div class="col-md-6 form-group">
                     <a href="{{Auth::user()->type == 'admin' || Auth::user()->hasPermission('Permission') ? url('/Admin/Permission') : url('/Leaverequest')}}"
                        class="btn btn-primary">Cancel</a>
                  </div>
                  <div class="col-md-6 form-group">
                     {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                  </div>

               </div>

               {!! Form::close() !!}
            </div>
         </div>
      </div>
   </div>
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
   <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


   <script>
      document.addEventListener('DOMContentLoaded', function () {
         flatpickr("#fromTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K", // 12-hour format with AM/PM
            time_24hr: false
         });

         flatpickr("#toTime", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "h:i K",
            time_24hr: false
         });

         const fromTime = document.getElementById('fromTime');
         const toTime = document.getElementById('toTime');
         const totalHours = document.getElementById('totalHours');

         function calculateHours() {
            if (fromTime.value && toTime.value) {
               const from = parseTime(fromTime.value);
               const to = parseTime(toTime.value);

               let start = new Date();
               start.setHours(from.hours, from.minutes, 0);

               let end = new Date();
               end.setHours(to.hours, to.minutes, 0);

               if (end <= start) {
                  end.setDate(end.getDate() + 1);
               }

               const diffMs = end - start;
               const totalMinutes = Math.floor(diffMs / (1000 * 60));
               const hours = Math.floor(totalMinutes / 60);
               const minutes = totalMinutes % 60;

               const hrLabel = hours === 1 ? 'hr' : 'hrs';
               totalHours.value =
                  `${hours.toString().padStart(2, '0')} ${hrLabel}` +
                  (minutes > 0 ? ` and ${minutes.toString().padStart(2, '0')} min` : '');
            }
         }

         function parseTime(timeStr) {
            const [time, modifier] = timeStr.split(' ');
            let [hours, minutes] = time.split(':').map(Number);

            if (modifier.toLowerCase() === 'pm' && hours < 12) hours += 12;
            if (modifier.toLowerCase() === 'am' && hours === 12) hours = 0;

            return { hours, minutes };
         }

         fromTime.addEventListener('change', calculateHours);
         toTime.addEventListener('change', calculateHours);
      });
   </script>




@endsection