<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content=""> 

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('theme/vendor/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{!! asset('theme/vendor/metisMenu/metisMenu.min.css') !!}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{!! asset('theme/dist/css/sb-admin-2.css') !!}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{!! asset('theme/vendor/morrisjs/morris.css') !!}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Custom Fonts -->
    <link href="{!! asset('theme/vendor/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>

    

</head>
<style type="text/css">
    
.manditary{
    color: red;
}

.supHigh
{
  color: red;  
}
.supMedium
{
  color: green;  
}
.supLow
{
  color: blue;  
}
.red-tooltip + .tooltip > .tooltip-inner {
  background-color: lightgreen;
  color:black;
  font-size: 15px
}
.red-tooltip + .tooltip > .tooltip-arrow { border-bottom-color:#f00; }
.fa {
    cursor: pointer;
}
.well {
    min-height: 0px;
    padding: 5px 15px 0px 15px;
    background-color: #f5f5f5;
    margin-top: 5px; margin-bottom: 10px;
}
.table-heading
{
  color:#5bc0de;
  font-size: 22px;
}
.select2-container .select2-selection--multiple {
  box-sizing: border-box;
  cursor: pointer;
  display: block;
  min-height: 34px;
  user-select: none;
  -webkit-user-select: none;
  border: 1px solid #ccc;
border-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
</style>
<body>

<audio class="audios" id="yes-audio" style="display: none" controls preload="none"> 
   <source src="{!! asset('theme/dist/sound/glass_ping.wav') !!}" type="audio/mpeg">
</audio>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include('theme.lms.header')
            @include('theme.lms.sidebar')
        </nav>

        <div id="page-wrapper">
            @yield('content')
        </div>
        <!-- /#page-wrapper -->
<!-- Modal -->
<div class="modal fade" id="systemModal" tabindex="-1" role="dialog" aria-labelledby="systemModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
     
      <div class="modal-body">
        <div class="row" style="margin-left:10px;margin-right:10px">
              @if( Auth::user()->type=='admin')
            <a type="button" class="btn btn-primary btn-block" href="{{url('/Admin/Task')}}">Task Management System</a>
            @else
            <a type="button" class="btn btn-primary btn-block" href="{{url('/Task')}}">Task Management System</a>
            @endif
        </div><br>
        <div class="row" style="margin-left:10px;margin-right:10px">
            <a type="button" class="btn btn-primary btn-block"  href="{{route('leads.list')}}">Lead Management System</a>
         
        </div><br>
        <div class="row" style="margin-left:10px;margin-right:10px">
            <a type="button" class="btn btn-primary btn-block"  onclick="changeSystem(3)">Recruit Management System</a>
        </div><br>
      
      
      </div>
      
    </div>
  </div>
</div>
    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{!! asset('theme/vendor/jquery/jquery.min.js') !!}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{!! asset('theme/vendor/bootstrap/js/bootstrap.min.js') !!}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{!! asset('theme/vendor/metisMenu/metisMenu.min.js') !!}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{!! asset('theme/vendor/raphael/raphael.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/morrisjs/morris.min.js') !!}"></script>
    <script src="{!! asset('theme/data/morris-data.js') !!}"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{!! asset('theme/dist/js/sb-admin-2.js') !!}"></script>
     <script type="text/javascript">
  $('#myselect').select2({
    width: '100%',
    placeholder: "Select an Option",
    allowClear: true
  });
  $('#myselectNotIN').select2({
    width: '100%',
    placeholder: "Select an Option",
    allowClear: true,

  });
    </script>
<script type="text/javascript">
function changeSystem(value)
{
    console.log(value);
    localStorage.setItem('system_url',value);
    var system_url  = localStorage.getItem('system_url');
    console.log(system_url);
}
function Delete(id)
{
    console.log(id,"id");
       
        $.ajax({
          type : 'get',
          url : 'leads/edit'+"/"+id,
          success:function(data){
            console.log(data);
            location.reload(); 
         } 
       });
}

    </script>
    
</body>

</html>