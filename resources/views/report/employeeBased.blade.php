@extends('theme.default')
@section('content')



    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
    <style>
        .header{
            padding: 10px;
        }
        /* --- Layout --- */
        .filter-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
            align-items: end;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 12px;
        
        }


        

        /* --- Buttons --- */

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0069d9;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
        }

        /* --- Total Hours Section --- */
        .total-hours {
            margin-top: 1rem;
            text-align: right;
            padding: 1rem;
            border-radius: 10px;
            
        }

        .total-hours strong {
            font-size: 16px;
            color: #333;
        }

        /* --- Period Buttons --- */
        .period-buttons {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        @media (max-width: 768px) {
            .total-hours {
                text-align: center;
            }

            .period-buttons {
                justify-content: center;
            }
        }
    </style>
    <div class="header">
        <h2>Employee Based</h2>
    </div>

    <div class="filter-container">
        <div>
            <label for="employeeFilter"><strong>Employee</strong></label>
            <select id="employeeFilter" class="form-control">
                <option value="">All Employees</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="fromDate"><strong>From Date</strong></label>
            <input class="form-control" type="date" id="fromDate">
        </div>

        <div>
            <label for="toDate"><strong>To Date</strong></label>
            <input class="form-control" type="date" id="toDate">
        </div>

        <div>
            <button class="btn btn-info" id="dateRangeBtn">Filter by Date Range</button>
        </div>
    </div>

    <div class="total-hours d-flex" style="display:flex;justify-content:space-between;">
        <strong>Total Working Hours: <span id="totalWorkingHoursTop">00:00</span></strong>

        <div class="period-buttons">
            <button class="btn btn-primary periodBtn" data-period="daily">Today</button>
            <button class="btn btn-primary periodBtn" data-period="weekly">This Week</button>
            <button class="btn btn-primary periodBtn" data-period="monthly">This Month</button>
            <button id="downloadExcel" class="btn btn-success">Download Excel</button>
        </div>
    </div>







    <div class="row">
        <div class="col-md-12" id="reportTable">
            @include('report.partials.employeeTable', ['reportData' => $reportData])
        </div>
    </div>

    <script>
        function fetchReport(period = null) {
            var employee = $('#employeeFilter').val();
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();

            // If custom date range is chosen, use that
            if (fromDate && toDate) {
                period = 'custom';
            } else {
                // otherwise, use active button or default daily
                period = period || $('.periodBtn.active').data('period') || 'daily';
            }

            $.ajax({
                url: "{{ url('Admin/Report/Employee-Report-Ajax') }}",
                type: "GET",
                data: { employee, period, fromDate, toDate },
                success: function (response) {
                    $('#reportTable').html(response);
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

        // Period buttons
        $('.periodBtn').click(function () {
            $('.periodBtn').removeClass('active');
            $(this).addClass('active');

            // Clear custom date range when clicking period buttons
            $('#fromDate').val('');
            $('#toDate').val('');

            fetchReport($(this).data('period'));
        });

        // Custom Date Range button
        $('#dateRangeBtn').click(function () {
            $('.periodBtn').removeClass('active'); // disable period selection
            fetchReport('custom');
        });

        // Employee dropdown
        $('#employeeFilter').change(function () {
            fetchReport();
        });

        // Default on load
        $(document).ready(function () {
            $('.periodBtn[data-period="daily"]').addClass('active');
            fetchReport('daily');
        });

        // Trigger AJAX when employee changes (keeps current period)
        $('#employeeFilter').change(function () {
            var period = $('.periodBtn.active').data('period') || 'daily';
            fetchReport(period);
        });

        // Handle period buttons
        $('.periodBtn').click(function () {
            $('.periodBtn').removeClass('active');
            $(this).addClass('active');

            var period = $(this).data('period');

            // Clear date filters if any
            $('#fromDate').val('');
            $('#toDate').val('');

            fetchReport(period);
        });

        // On page load, show Today by default
        $(document).ready(function () {
            $('.periodBtn[data-period="daily"]').addClass('active');
            fetchReport('daily');
        });
        // CSV Download button
        $('#downloadExcel').click(function () {
            var table = document.getElementById('employeeReportTable');

            var employeeName = $('#employeeFilter option:selected').text() || 'All';
            var period = $('.periodBtn.active').data('period') || 'daily';
            var filename = employeeName + "_" + period + "_Report.xlsx";

            TableToExcel.convert(table, { name: filename, sheet: { name: "Report" } });
        });
        $('#employeeFilter').change(function () {
            var period = $('.periodBtn.active').data('period') || 'daily';
            fetchReport(period);
        });

        $('.periodBtn').click(function () {
            $('.periodBtn').removeClass('active');
            $(this).addClass('active');

            var period = $(this).data('period');
            fetchReport(period);
        });

        function fetchReport(period) {
            var employee = $('#employeeFilter').val();
            $.ajax({
                url: "{{ url('Admin/Report/Employee-Report-Ajax') }}",
                type: "GET",
                data: { employee: employee, period: period },
                success: function (response) {
                    $('#reportTable').html(response);
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