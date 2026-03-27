@extends('theme.default')
@section('content')
   @include('theme.filterScript')
   <div class="well">
      <div class="row">
         <?php   $employees = App\Employee::all();?>
         <div class="form-group">
            <div class="col-md-3">
               <label>Employees</label>
               <select class="form-control" id="employeeFilter">
                  <option value="">Select one</option>
                  @foreach($employees as $key => $emp)
                     <option value="{{$emp->id}}">{{$emp->name}}</option>
                  @endforeach
               </select>
            </div>
         </div>
         <div class="col-md-3">
            <div class="form-group">
               <label>Activity</label>
               <select class="form-control" id="activityFilter">
                  <option value="">Select one</option>
                  @foreach(App\CfgActivity::all() as $activity)
                     <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                  @endforeach
               </select>

            </div>
         </div>


         <div class="col-md-2">
            <div class="form-group">
               <label>From Date</label>
               <input placeholder="From Date" onfocus="(this.type='date')" onblur="(this.type='text')"
                  name="fromDateFilter" id="fromDateFilter" class="form-control">
            </div>
         </div>

         <div class="col-md-2">
            <div class="form-group">
               <label>To Date</label>
               <input placeholder="To Date" onfocus="(this.type='date')" onblur="(this.type='text')" name="toDateFilter"
                  id="toDateFilter" class="form-control">
            </div>
         </div>
         <div class="col-md-1">
            <br>
            <div class="form-group">
               <a class="btn btn-primary" onclick="Search()"><i class="fa fa-search" aria-hidden="true"
                     title="Search"></i></a>
            </div>
         </div>
         <div class="col-md-1">
            <br>
            <div class="form-group">
               <a class="btn btn-primary" onclick="resetFilter()"><i class="fa fa-refresh" aria-hidden="true"
                     title="Reset Filter"></i></a>
            </div>
         </div>
      </div>


      <div class="row">




      </div>
   </div>
   <div class="row">
      <div class="col-lg-12 well">
         <h3>All Tasks</h3>
         <div id="reportfilter">
            @include('reports.partials.reportfilter')
         </div>
      </div>

      <script type="text/javascript">
         $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
         });
         $('.clicker').click(function () {

            $(this).nextUntil('.clicker').slideToggle('normal');
         });

         function viewEmployee() {
            var empId = $("#employeeFilter option:selected").val();
            window.location.href = "{{URL::to('/Admin/Report')}}/" + empId;


         }
      </script>
      <script>
         function Search() {
            var employee = document.getElementById("employeeFilter").value;
            var fromDate = document.getElementById("fromDateFilter").value;
            var toDate = document.getElementById("toDateFilter").value;
            var activity = document.getElementById("activityFilter").value;

            $.ajax({
               type: 'get',
               url: '{{ URL::to("Report/Filter") }}',
               data: {
                  'project': null,
                  'employee': employee,
                  'fromDate': fromDate,
                  'toDate': toDate,
                  'activity': activity
               },
               success: function (data) {
                  $('#reportfilter').empty();
                  $('#reportfilter').html(data['task']);
               },
               error: function (xhr, status, error) {
                     if (xhr.status === 419) {
                        alert('CSRF token mismatch');
                        location.reload(true);
                     } else {
                        console.error("Error: " + error);
                        alert('An error occurred. Please try again later.');
                     }
               } 
            });

         }
      </script>
      <script>
         function resetFilter() {
            document.getElementById("employeeFilter").value = '';
            document.getElementById("fromDateFilter").value = '';
            document.getElementById("toDateFilter").value = '';
            document.getElementById("activityFilter").value = '';

            $.ajax({
               type: 'get',
               url: '{{ URL::to("Report/Filter") }}',
               data: {
                  'project': null,
                  'employee': '',
                  'fromDate': '',
                  'toDate': '',
                  'activity': ''
               },
               success: function (data) {
                  $('#reportfilter').empty();
                  $('#reportfilter').html(data['task']);
               },
               error: function (xhr, status, error) {
                     if (xhr.status === 419) {
                        alert('CSRF token mismatch');
                        location.reload(true);
                     } else {
                        console.error("Error: " + error);
                        alert('An error occurred. Please try again later.');
                     }
               } 
            });
         }

      </script>
@endsection