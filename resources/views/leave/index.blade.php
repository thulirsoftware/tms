@extends('theme.default')

@section('content')
<style>
    .badge-success {
        background-color: green;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-warning {
        color: #212529;
        background-color: #ffc107;
    }

    ul.nav.nav-pills.mt-5 {
        margin-top: 3.5rem;
    }
    .mt-3 {
        margin-top: 1rem;
    }
</style>

<?php

$employees = App\Employee::all();

?>
<div class="row">
    <div class="col-lg-9" style="padding-bottom: 10px; ">
        <h1 class="page-header">List of Employees Leave Reports</h1>
    </div>
    <div class="col-lg-3" style="padding-bottom: 10px;float: right;">
        <a href="{{ route('admin.leavehome') }}" class="btn btn-md btn-info page-header">Employees Leave</a>
    </div>
</div>

<ul class="nav nav-pills">
    <li class="@if($active_tab =='leave') active @endif"><a data-toggle="pill" href="#leave">Leave</a></li>
    <li class="@if($active_tab =='permissions') active @endif"><a data-toggle="pill" href="#permissions">Permissions</a></li>
</ul>

<div class="tab-content">
    <div id="leave" class="tab-pane fade @if($active_tab =='leave') in active @endif">
        <div class="col-lg-3 form-group">
            <select name="employee" id="employee" class="form-control mt-3">
                <option value="">Select Employee</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3" style="padding-top: 10px">
            <a href="/Admin/Leave" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i>
            </a>
        </div>
        
    <table class="table table-striped table-bordered table-hover">
        <?php
    $i = 1;
                ?>
        <thead>
            <tr>
                <th>ID</th>
                <th>Requested Date</th>
                <th>Name</th>
                <th>Leave Type</th>
                <th>From Date</th>
                <th>To Date</th>
                <th>Total days</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @include('leave.leave_report_filter')
        </tbody>
    </table>
    </div>
    <div id="permissions" class="tab-pane fade @if($active_tab =='permissions') in active @endif ">
        <div class="col-lg-3 form-group">
            <select name="employee" id="employeePermissions" class="form-control mt-3">
                <option value="">Select Employee</option>
                @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-3" style="padding-top: 10px">
            <a href="/Admin/Leave?active_tab=permissions" class="btn btn-info"><i class="fa fa-refresh" aria-hidden="true"></i>
            </a>
        </div>
          <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                
                <th>Name</th>
                <th>Requested Date</th>
                <th>Permission Date</th>
                <th>From Time</th>
                <th>To Time</th>
                <th>Total Hours</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @include('leave.permission_report_filter')
        </tbody>
    </table>
    </div>
</div>



<script type="text/javascript">
    // $(document).ready(function(){
    // startTime();
    // });
    $('.clicker').click(function() {

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
        if (i < 10) {
            i = "0" + i
        }; // add zero in front of numbers < 10
        return i;
    }

    function changeStatus(LeaveId, LeaveEMpId, status) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $.ajax({
            method: "POST",
            url: "{{ route('ApproveLeave') }}",
            data: {
                leaveId: LeaveId,
                empId: LeaveEMpId,
                status: status
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
               
                     let url = new URL(window.location.href);
                    url.searchParams.set('active_tab', 'leave'); 
                     window.location.href = url.toString();
            } else {
                alert('Try Again!');
                let url = new URL(window.location.href);
                    url.searchParams.set('active_tab', 'leave'); 
                     window.location.href = url.toString();
            }
        }).fail(function(xhr, status, error) {
            if (xhr.status === 419) {
                alert('CSRF token mismatch');
                 let url = new URL(window.location.href);
                    url.searchParams.set('active_tab', 'leave'); 
                     window.location.href = url.toString();
            } else {
                console.error("Error: " + error);
                alert('An error occurred. Please try again later.');
            }
        });
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
            data: {
                leaveId: leaveId,
                time: time
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
                location.reload(true);
            } else {
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
            data: {
                leaveId: leaveId,
                time: time,
                leaveStatus: leaveStatus,
                leaveComment: leaveComment
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
                location.reload(true);
            } else {
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

    function approveTask(leaveId, empId) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $.ajax({
            method: "POST",
            url: "{{ route('ApproveLeave') }}",
            data: {
                leaveId: leaveId,
                empId: empId
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
                location.reload(true);
            } else {
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

    function declineTask(leaveId, empId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $.ajax({
            method: "POST",
            url: "{{ route('DeclineLeave') }}",
            data: {
                leaveId: leaveId,
                empId: empId
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
                location.reload(true);
            } else {
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

    function declineTask1(leaveId) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $.ajax({
            method: "POST",
            url: "{{ route('DeclineLeave') }}1",
            data: {
                leaveId: leaveId
            }
        }).done(function(data) {
            console.log(data);
            if (data.status == true) {
                location.reload(true);
            } else {
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
</script>

<script type="text/javascript">
    $('#employee').on('change', function() {
        $value = $(this).val();
        window.location.href = '/Admin/Leave?active_tab=leave&employee=' + $value;
    })
      $('#employeePermissions').on('change', function() {
        $value = $(this).val();
        window.location.href = '/Admin/Leave?active_tab=permissions&employee=' + $value;
    })
</script>


@endsection