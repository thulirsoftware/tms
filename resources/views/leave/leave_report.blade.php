@extends('theme.default')

@section('content')
    <div style="padding-top: 30px">
    </div>
    <a href="/Admin/Leave"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i>
    </a>
    <div style="text-align:center;font-size:25px;font-weight:bold;margin-top:-25px">
        Leave Report - {{ $month }}

    </div>
    <div style="padding-top: 10px">
    </div>
    <table class="table table-bordered table-hover" style="text-align:center;">
        <?php
    $i = 1;
            ?>

        <?php
    $employees = App\Employee::all();
            ?>

        <thead style="background-color: #d3ebc5;">
            <tr>
                <th>Employee ID</th>
                <th>Employee Name</th>
                <th>Assigned Leave</th>
                <th>Taken Leave</th>
                <th>Available Leaves</th>
                <th>Days to Compensate</th>
                <th colspan="2">Add / Decrement<br>CASUAL LEAVE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                    <?php
                $assignedLeave = App\EmployeesLeave::where('empId', $employee->empId)->first();
                // Initialize variables with default values
                $cl = $assignedLeave ? ($assignedLeave->el ?? 0) : 0;
                $taken = $assignedLeave ? ($assignedLeave->taken ?? 0) : 0;
                $available = $cl - $taken;
                $compensate = $assignedLeave ? ($assignedLeave->compensate ?? 0) : 0;
                                ?>
                    <tr>
                        <td>{{ $employee->empId }}</td>
                        <td>{{ $employee->name }}</td>
                        <td>{{ $cl }}</td>
                        <td>{{ $taken }}</td>
                        <td>{{ $available }}</td>
                        <td>{{ $compensate }}</td>
                        <td>
                            <i id="addLeave{{ $employee->empId }}"
                                onclick="return confirm('Add 1 CL ?') && addLeave({{ $employee->empId }})" class="fa fa-plus"
                                style="font-size:25px;color:green"></i>
                        </td>
                        <td>
                            <i id="decLeave{{ $employee->empId }}"
                                onclick="return confirm('Decrement 1 CL ?') && decLeave({{ $employee->empId }})" class="fa fa-minus"
                                style="font-size:25px;color:green"></i>
                        </td>
                    </tr>
            @endforeach
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript">

        function addLeave(empId) {
            console.log('Adding CL for empId:', empId);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: "POST",
                url: "{{ route('addCL') }}",
                data: { empId: empId }
            }).done(function (data) {
                console.log('Response:', data);
                if (data.status == true) {
                    location.reload(true);
                } else {
                    alert('Try Again! ' + (data.message || ''));
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

        function decLeave(empId) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{csrf_token()}}'
                }
            });

            $.ajax({
                method: "POST",
                url: "{{ route('decCL') }}",
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

    </script>


@endsection