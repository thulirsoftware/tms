@extends('theme.default')
@section('content')
    @include('theme.filters')
    <div class="row">
        <div class="col-md-10">


        </div>
        <div class="col-md-2">
            <input type="button" value="Download as Excel" class="btn btn-primary mt-2 float-right"
                onclick="exportBiometricToExcel('biometric_reports_valid','reports')">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 well">
            <h3>All Tasks</h3>
            <table class="table table-striped table-bordered table-condensed  table-hover" id="biometric_reports_valid">
                <thead>
                    <tr>
                        <th>As.Date</th>
                        <th>Taken Date</th>
                        <th>Employee</th>
                        <th>Project</th>
                        <th>Activity</th>
                        <th>Instruction</th>
                        <th>Comment</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>HH:MM</th>
                         <th>Total Hours</th>
                        <th>Current Status</th>
                    </tr>
                </thead>
                @php
                    $hasFilters = collect(request()->only([
                        'project','activity','employee','status',
                        'assignedDate','takenDate','fromDate','toDate'
                    ]))->filter()->isNotEmpty();
                @endphp
            
            @if($hasFilters)
                @include('report.partials.taskReportTable')
            
            @endif
            </table>
        </div>
        <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>
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
        <script type="text/javascript">


            function exportTableToExcel(tableID, filename, fn, dl) {
                var elt = document.getElementById(tableID);
                var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
                return dl ?
                    XLSX.write(wb, { bookType: 'xlsx', bookSST: true, 'xlsx': 'base64' }) :
                    XLSX.writeFile(wb, fn || (filename + '.xlsx'));
            }

            function exportBiometricToExcel(tableID) {
                TableToExcel.convert(document.getElementById(tableID));

            }
        </script>
@endsection