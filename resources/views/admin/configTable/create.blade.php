@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Create {{$tableName}} Record</h1>
      </div>
      <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
   <div class="row">
      <div class="col-md-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Table Informations
            </div>
            <div class="panel-body">
               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif
               {!! Form::model($tableRow, [
      'method' => 'POST',
      'url' => ["/Admin/ConfigTable/" . base64_encode($tableName) . "/store"],
      'class' => 'form-horizontal',
      'id' => 'task',
      'files' => true
   ]) !!}
               <input type="hidden" name="_token" value="{{csrf_token()}}">
               <div class="col-md-12">
                  <table class="table table-striped table-bordered table-hover table-condensed ">
                     <thead>
                        <tr>
                           <th colspan="2">Field Name</th>
                           <th colspan="2">Values</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($tableColumns as $key => $column)
                           <tr>

                              <td colspan="2"><label>{{$column}}</label></td>
                              <td colspan="2"><input class="form-control" type="text" name="{{$column}}" value=""></td>
                           </tr>

                        @endforeach
                        <tr>
                           <th colspan="4" style="text-align: center">
                              <a href="{{Auth::user()->type == 'admin' ? url('/Admin/ConfigTable') : url('/ConfigTable')}}/{{base64_encode($tableName)}}/show"
                                 class="btn btn-primary">Cancel</a>
                              {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Submit', ['class' => 'btn btn-primary']) !!}
                           </th>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>

   {!! Form::close() !!}


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