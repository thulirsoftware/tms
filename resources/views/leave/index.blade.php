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
    </style>
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List of Employees Leave Reports</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="col-lg-3" style="padding-bottom: 20px">
        <a href="{{ route('admin.leavehome') }}" class="btn btn-info">Employees Leave</a>
    </div>
    <?php

    $employees = App\Employee::all();

        ?>

    <div class="col-lg-3 form-group">
        <select name="employee" id="employee" class="form-control">
            <option value="">Select Employee</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ request()->get('employee') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- <div class="col-lg-3" style="padding-bottom: 20px">
            <a href="/Admin/Leave/leaveReport" class="btn btn-info">Leave Report</i>
            </a>
        </div> -->

    <div class="col-lg-3" style="padding-bottom: 20px">
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
    <h2 class="mt-5">Employees Permission Reports</h2>

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
        function changeStatus(LeaveId, LeaveEMpId, status) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            $.ajax({
                method: "POST",
                url: "{{ route('ApproveLeave') }}",
                data: { leaveId: LeaveId, empId: LeaveEMpId, status: status }
            }).done(function (data) {
                console.log(data);
                if (data.status == true) {
                    location.reload(true);
                }
                else {
                    alert('Try Again!');
                    location.reload(true);
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
                data: { leaveId: leaveId, empId: empId }
            }).done(function (data) {
                console.log(data);
                if (data.status == true) {
                    location.reload(true);
                }
                else {
                    alert('Try Again!');
                    location.reload(true);
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
                data: { leaveId: leaveId, empId: empId }
            }).done(function (data) {
                console.log(data);
                if (data.status == true) {
                    location.reload(true);
                }
                else {
                    alert('Try Again!');
                    location.reload(true);
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
            });

        }
    </script>

    <script type="text/javascript">
        $('#employee').on('change', function () {
            $value = $(this).val();
            window.location.href = '/Admin/Leave?employee=' + $value;
        })




    </script>


@endsection