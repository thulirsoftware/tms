@extends('theme.default')

@section('content')
    <div class="row">
        <br>
        <div class="col-lg-12 well">
            <h3>Presence / Leave Details
                <div style="float: right;">
                    @if(Auth::user()->type == "admin")
                        <!---<button class="btn btn-info"  ><i class="fa fa-minus"></i> Leave</button>&nbsp<button class="btn btn-info" ><i class="fa fa-plus"></i> Leave </button>-->
                    @endif
                    @if(Auth::user()->type != "admin")
                        <a href="{{url('/Leave-permission')}}/{{$employee->id}}/create-permission"
                            class="btn btn-info btn-rounded"><i class="fa fa-plus" aria-hidden="true"
                                onclick="permissionLeave({{$employee->id}})"></i>Permission</a>
                    @endif

                    @if(Auth::user()->type != "admin")
                        <a href="{{url('/Leave')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded"><i
                                class="fa fa-plus" aria-hidden="true" onclick="casualLeave({{$employee->id}})"></i> Leave
                            Request</a>
                    @endif

                    <?php 

                                      $joinDate = $employee->joinDate;

    $today = Carbon\Carbon::now()->toDateString();

    $ts1 = strtotime($joinDate);
    $ts2 = strtotime($today);

    $year1 = date('Y', $ts1);
    $year2 = date('Y', $ts2);

    $month1 = date('m', $ts1);
    $month2 = date('m', $ts2);

    $noOfMonth = (($year2 - $year1) * 12) + ($month2 - $month1);

                                      ?>

                    <input type="hidden" name="casualId" id="casualId" value="{{$employee->id}}">

                    <input type="hidden" name="noOfMonth" id="noOfMonth" value="{{$noOfMonth}}">

                </div>
            </h3>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Request Date</th>
                        <th>Leave Type</th>
                        <th>Reason</th>
                        <th>No.of Days</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves as $leave)
                        <tr>
                            <td>{{$leave->requestDate}}</td>
                            <td>{{$leaveTypes[$leave->leaveTypeId]}}</td>
                            <td>{{$leave->reason}}</td>


                            @if($leave->priority != null)
                                <sup><i
                                        class="fa fa-flag {{($leave->priority != 0) ? (($leave->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                            @endif
                            </td>
                            <td>{{$leave->totalLeaveDays}}</td>
                            <td>{{$leave->leaveFromDate}}</td>
                            <td>{{$leave->leaveToDate}}</td>




                            <td>
                                @if(Auth::user()->type != 'admin')
                                    @if($leave->approval == 'yes')
                                        <i id="startTimer<?=$leave->id?>" class="fa fa-thumbs-up"
                                            style="font-size:25px;color:green"></i>
                                        &nbsp;
                                    @elseif($leave->approval == 'no')
                                        <i id="approveTask<?=$leave->id?>" class="fa fa-thumbs-down"
                                            style="font-size:25px;color:pink"></i>
                                    @elseif($leave->approval == '')
                                        <i id="approveTask<?=$leave->id?>" class="fa fa-spinner" style="font-size:25px;color:blue"></i>
                                    @endif



                                @else
                                    @if($leave->approval == 'no' || $leave->approval == '')
                                        <i id="declineTask<?=$leave->id?>" onclick="approveTask({{$leave->id}})" class="fa fa-thumbs-up"
                                            style="font-size:25px;color:green"></i>
                                    @else
                                        <i id="approveTask<?=$leave->id?>" onclick="declineTask({{$leave->id}})"
                                            class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                                    @endif
                                @endif

                                {{-- @if(Auth::user()->id==$leave->assignedBy||Auth::user()->type=='admin')
                                <a href="{{url('/Task')}}/{{$leave->id}}/edit"><i class="fa fa-edit"
                                        style="font-size:20px;color:blue"></i></a>&nbsp;
                                <a href="{{url('/Task')}}/{{$leave->id}}/destroy"><i class="fa fa-trash"
                                        style="font-size:20px;color:red"></i></a>
                                @endif --}}
                            </td>



                        </tr>
                    @endforeach
                </tbody>
            </table>
            <h4 class="mt-4">Permission Requests</h4>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Request Date</th>
                        <th>Permission Date</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Total Hours</th>
                        <th>Reason</th>
                        <th>Status / Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $permission)
                        <tr>
                            <td>{{ $permission->requestDate }}</td>
                            <td>{{ $permission->permissionDate }}</td>
                            <td>{{ $permission->fromTime }}</td>
                            <td>{{ $permission->toTime }}</td>
                            <td>
                                @php
                                    $totalHours = $permission->totalHours; // e.g., 2.33
                                    $hours = floor($totalHours);
                                    $minutes = round(($totalHours - $hours) * 60);
                                @endphp
                                {{ sprintf('%02d', $hours) }}hrs {{ sprintf('%02d', $minutes) }}mins
                            </td>

                            <td>{{ $permission->reason }}</td>
                            <td>
                                @if(Auth::user()->type != 'admin')
                                    {{-- Employee view --}}
                                    @if($permission->approval == 'yes')
                                        <i class="fa fa-thumbs-up" style="font-size:25px;color:green"></i>
                                    @elseif($permission->approval == 'no')
                                        <i class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                                    @else
                                        <i class="fa fa-spinner" style="font-size:25px;color:blue"></i>
                                    @endif
                                @else
                                    {{-- Admin view with toggle --}}
                                    @if($permission->approval == 'no' || $permission->approval == '')
                                        <i id="approvePermission{{$permission->id}}" onclick="approvePermission({{$permission->id}})"
                                            class="fa fa-thumbs-up" style="font-size:25px;color:green"></i>
                                    @else
                                        <i id="declinePermission{{$permission->id}}" onclick="declinePermission({{$permission->id}})"
                                            class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                                    @endif
                                @endif
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No permission requests found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

        <script type="text/javascript">
            // $(document).ready(function(){
            // startTime();
            // });
            $('.clicker').click(function () {

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
                if (i < 10) { i = "0" + i };  // add zero in front of numbers < 10
                return i;
            }
            function startTime(leaveId) {
                var time = getTime();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('SetStartTime') }}",
                    data: { leaveId: leaveId, time: time }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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
            function stopTime(leaveId) {
                var time = getTime();
                var valid = true;
                $('span.error', this).remove();
                if (!$('#leaveComment').val()) {
                    valid = false;
                    $("#leaveComment").focus();
                    $('<span class="alert-danger" style="text-align:center;" >Please give Comment</span>').
                        insertAfter('#leaveComment');
                }
                if (!$('#leaveStatus').val()) {
                    valid = false;
                    $("#leaveStatus").focus();
                    $('<span class="alert-danger" style="text-align:center;" >Choose status!</span>').
                        insertAfter('#leaveStatus');
                }
                var leaveComment = $("#leaveComment").val();
                var leaveStatus = $("#leaveStatus").find('option:selected').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('SetEndTime') }}",
                    data: { leaveId: leaveId, time: time, leaveStatus: leaveStatus, leaveComment: leaveComment }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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
            function approveTask(leaveId) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ApproveLeave') }}",
                    data: { leaveId: leaveId }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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

            function declineTask(leaveId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('DeclineLeave') }}",
                    data: { leaveId: leaveId }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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


            function addLeave(empId) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('AddLeave') }}",
                    data: { empId: empId }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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


            function reduceLeave(empId) {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ReduceLeave') }}",
                    data: { empId: empId }
                }).done(function (data) {
                    console.log(data);
                    if (data.status == true) {
                        location.reload(true);
                    }
                    else {
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




            $(document).ready(function () {

                var empId = $('#casualId').val();
                var noOfMonth = $('#noOfMonth').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('CasualLeave') }}",
                    data: { empId: empId, noOfMonth: noOfMonth }
                }).done(function (data) {
                    console.log(data);
                }).fail(function(xhr, status, error) {
                 if (xhr.status === 419) {
                    alert('CSRF token mismatch');
                    location.reload(true);
                } else {
                    console.error("Error: " + error);
                    alert('An error occurred. Please try again later.');
                }
            });

            });
            function approvePermission(permissionId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ApprovePermission') }}",
                    data: { permissionId: permissionId }
                }).done(function (data) {
                    if (data.status === true) {
                        location.reload(true);
                    } else {
                        alert('Try Again!');
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

            function declinePermission(permissionId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('DeclinePermission') }}",
                    data: { permissionId: permissionId }
                }).done(function (data) {
                    if (data.status === true) {
                        location.reload(true);
                    } else {
                        alert('Try Again!');
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



        </script>
@endsection