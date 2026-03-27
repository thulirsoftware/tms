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
   table td, .table th {
  padding: .75rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
.btn-light {
  color: #212529;
  background-color: #f8f9fa;
  border-color: #f8f9fa;
}
.btn-light:not(:disabled):not(.disabled).active, .btn-light:not(:disabled):not(.disabled):active, .show > .btn-light.dropdown-toggle {
  color: #212529;
  background-color: #dae0e5;
  border-color: #d3d9df;
}
.btn {
  display: inline-block;
  font-weight: 400;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  border: 1px solid transparent;
    border-top-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    border-left-color: transparent;
  padding: .675rem 1.95rem;
  font-size: 1rem;
  line-height: 1.9;
  border-radius: .25rem;
  transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
} 
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
.fancySearchRow,th
{
    border:none !important;
}
.fancySearchRow 
{
    border:none !important;
}
.fancySearchRow,input{
 padding: 6px 12px;
font-size: 14px;
line-height: 1.42857143;
color: #555;
background-color: #fff;
background-image: none;
border: 1px solid #ccc;
border-radius: 4px;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 4px;
-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}
.btn.active, .btn:active {
  background-image: none;
  outline: 0;
  -webkit-box-shadow: none;
  box-shadow: none;
}
 
#mySelect2 {
  width: 100%;
  min-height: 100px;
  border-radius: 3px;
  border: 1px solid #444;
  padding: 10px;
  color: #444444;
  font-size: 14px;
}
.daterangepicker {
  background-color: #fff;
  border-radius: 0 !important;
  align-content: center !important;
  padding: 0 !important;
}
.month {
  font-size: 16px !important;
  padding-bottom: 10px !important;
  padding-top: 10px !important;
}

.start-date, .end-date {
  border-radius: 0px !important;
}

.available:hover {
  border-radius: 0px !important;
}

.off {
  color: #EEEEEE !important;
}

.off:hover {
  background-color: #EEEEEE !important;
  color: #fff !important;
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
  
   <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script type="text/javascript">
      $(function () {
       
  $('#mySelect2').each(function () {
    $(this).select2({

        placeholder: $(this).attr('placeholder'),
      allowClear: Boolean($(this).data('allow-clear')),
    });
  });
});
  </script>
</body>

</html>