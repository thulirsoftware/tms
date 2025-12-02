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

    <!-- Custom Fonts -->
    <link href="{!! asset('theme/vendor/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
body {
    font-family: Arial, sans-serif !important;
}
table {
    font-family: Arial, sans-serif !important;
}
.dropdown-tasks{
      padding-left: 10px;
    padding-right: 10px;
}
#taskNotify li:nth-child(n+3) {
    padding-bottom: 10px;
    border-bottom: 2px dotted #dadada;
    margin-bottom: 10px;
}
#taskNotify .divider {
 
    display: none !important;
 
}
ul.dropdown-menu.dropdown-tasks {
    overflow-y: scroll;
    height: 300px;
}
</style>
<body>

<audio class="audios" id="yes-audio" style="display: none" controls preload="none"> 
   <source src="{!! asset('theme/dist/sound/glass_ping.wav') !!}" type="audio/mpeg">
</audio>
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            @include('theme.header')
            @include('theme.sidebar')
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
            <a type="button" class="btn btn-primary btn-block" href="{{url('/Employee')}}">Task Management System</a>
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
     <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{!! asset('theme/vendor/metisMenu/metisMenu.min.js') !!}"></script>

    <!-- Morris Charts JavaScript -->
    <script src="{!! asset('theme/vendor/raphael/raphael.min.js') !!}"></script>
    <script src="{!! asset('theme/vendor/morrisjs/morris.min.js') !!}"></script>
    <script src="{!! asset('theme/data/morris-data.js') !!}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{!! asset('theme/dist/js/sb-admin-2.js') !!}"></script>
     <script type="text/javascript">
        $(document).ready(function(){
            $( "label" ).each(function( index ) {
             var label = $( this ).html();
             // console.log(label);
             label = label.replace('`\*`','<span class="manditary">*</span>');
             $(this).html(label);
            });

             $( "th" ).each(function( index ) {
             var th = $( this ).html();
             // console.log(th);
             th = th.replace('`\*`','<span class="manditary">*</span>');
             $(this).html(th);
            });
        });
        $
    </script>
<script type="text/javascript">

$(document).ready(function(){
  setTimeout(checkNewNotification,5000);
});

   function checkNewNotification() { 

      $.ajax({
      url: "{{ route('CheckNewNotification') }}",
      type: 'get',
      
      success: function(data){
               if(data.status==true)
                    {
        var txt='<i class="fa fa-bell fa-fw" ></i><span class="badge badge-notify" style="background-color: red">'+data.count+'</span><i class="fa fa-caret-down"></i>';
                        $('#taskAlertBell').html(txt);
                        $('#yes-audio').trigger('play');
                     window.setTimeout(function(){
                     window.location.reload();
                     return false;
                     exit();
                   }, 1000);

                    }
                    else
                    {
                      return false;
                      exit();
                    } 
      },
        error: function (xhr, status, error) {
            if (xhr.status === 419) {
                alert('CSRF token mismatch');
                location.reload(true);
            } else {
                console.error("Error: " + error);
                alert('An error occurred. Please try again later.');
            }
        }, 
      complete:function(data){
       setTimeout(checkNewNotification,5000);
      }
     });

}


      $('#taskNotify').click(function(){
        //$.get('/MarkAsRead');
      });
      // $('#taskNotify').click(function(){
      //     $.get("{{ route('MarkAsRead') }}", function(data) {
      //         console.log(data);
      //     });
      // });

    </script>
</body>

</html>