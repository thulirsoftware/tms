@extends('theme.default')

@section('content')

    <div class="well" id="filters" style="padding:10px;">
        <h3>Project Based Report</h3>
        <div class="row">
            <div class="col-md-3">
                <label>Project</label>
                <select id="projectFilter" class="form-control">
                    <option value="">All Projects</option>
                    @foreach($projects as $key => $name)
                        <option value="{{ $key }}" {{ request()->project == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>From Date</label>
                <input type="date" id="fromDateFilter" class="form-control" value="{{ request()->fromDate }}">
            </div>

            <div class="col-md-3">
                <label>To Date</label>
                <input type="date" id="toDateFilter" class="form-control" value="{{ request()->toDate }}">
            </div>
            <div class="col-md-3" style="margin-top:24px;text-align:right">
                <button id="downloadExcel" class="btn btn-success">
                    <i class="fa fa-download"></i> Download as Excel
                </button>
            </div>
        </div>
    </div>



    <div class="well mt-2">

        @if(!empty($reportData))

            <div style="background-color:#f5f5f5;font-weight:bold; float: end;text-align:end;">
                <td colspan="3" class="text-right">Grand Total Hours:</td>
                <td style="color:green;">{{ $grandTotal }}</td>
            </div>

        @endif
        <table class="table table-bordered table-striped" id="projectReportTable">
            <thead class="thead-dark">
                <tr>
                    <th>Date</th>
                    <th>Project Name</th>
                    <th>Activity</th>
                    <th>Total Hours</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $data)
                    <tr>
                        <td>{{ date('M d, Y', strtotime($data['date'])) }}</td>
                        <td>{{ $data['project'] }}</td>
                        <td>{{ $data['activity'] }}</td>
                        <td style="color:red;">{{ $data['total_hours'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No data found</td>
                    </tr>
                @endforelse
            </tbody>


        </table>
    </div>

    {{-- Excel Export Script --}}
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

    <script>
        $(function () {
            // Auto filter refresh
            $('#projectFilter, #fromDateFilter, #toDateFilter').on('change', function () {
                var project = $('#projectFilter').val();
                var fromDate = $('#fromDateFilter').val();
                var toDate = $('#toDateFilter').val();

                // Dynamic path for both Admin/User
                var baseUrl = window.location.origin + window.location.pathname;

                window.location.href = baseUrl + '?project=' + project + '&fromDate=' + fromDate + '&toDate=' + toDate;
            });

            // Excel download
            $('#downloadExcel').click(function () {
                var table = document.getElementById('projectReportTable');

                var projectName = $('#projectFilter option:selected').text() || 'All_Projects';
                var fromDate = $('#fromDateFilter').val() || 'Start';
                var toDate = $('#toDateFilter').val() || 'End';
                var filename = projectName + "_(" + fromDate + "_to_" + toDate + ")_Report.xlsx";

                TableToExcel.convert(table, {
                    name: filename,
                    sheet: { name: "Project Report" }
                });
            });
        });
    </script>

@endsection