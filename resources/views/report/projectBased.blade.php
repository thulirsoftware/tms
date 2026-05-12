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

            <div class="col-md-2">
                <label>From Date</label>
                <input type="date" id="fromDateFilter" class="form-control" value="{{ request()->fromDate }}">
            </div>

            <div class="col-md-2">
                <label>To Date</label>
                <input type="date" id="toDateFilter" class="form-control" value="{{ request()->toDate }}">
            </div>

            <div class="col-md-2" style="margin-top:24px;text-align:right">
                <button onclick="applyFilters()" class="btn btn-primary mr-2">
                    Filter By Selection
                </button>
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
                <td style="color:green;">{{ $grandTotalHours }} hr {{ $grandTotalMinutes }} min</td>
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
        @if ($reportData && count($reportData) > 0)
            <div style="width: 100%; margin: 15px 0;">

                {{-- TOP: Showing --}}
                <div style="display: flex; justify-content: flex-end; margin-bottom: 5px; font-size: 13px; color: #777;">
                    Showing {{ $tasks->firstItem() }}–{{ $tasks->lastItem() }} of {{ $tasks->total() }}
                </div>

                {{-- BOTTOM: Rows + Pagination --}}
                <div style="display: flex; justify-content: flex-end; align-items: center;">

                    {{-- Rows --}}
                    <form method="GET" id="perPageForm" style="display: flex; align-items: center; margin-right: 15px;">
                        @foreach(request()->except('per_page') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach

                        <label style="margin-right: 5px; margin-bottom: 0;">Rows:</label>
                        <select name="per_page" onchange="this.form.submit()" style="width: auto;">
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200</option>
                            <option value="500" {{ request('per_page') == 500 ? 'selected' : '' }}>500</option>
                        </select>
                    </form>

                    {{-- Pagination --}}
                    <ul class="pagination" style="margin: 0;">

                        {{-- Prev --}}
                        @if ($tasks->onFirstPage())
                            <li class="page-item disabled"><span class="page-link">Prev</span></li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $tasks->previousPageUrl() }}">Prev</a>
                            </li>
                        @endif

                        @php
                            $start = max($tasks->currentPage() - 2, 1);
                            $end = min($tasks->currentPage() + 2, $tasks->lastPage());
                        @endphp

                        @if ($start > 1)
                            <li class="page-item"><a class="page-link" href="{{ $tasks->url(1) }}">1</a></li>
                            @if ($start > 2)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                        @endif

                        @for ($i = $start; $i <= $end; $i++)
                            <li class="page-item {{ $tasks->currentPage() == $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ $tasks->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($end < $tasks->lastPage())
                            @if ($end < $tasks->lastPage() - 1)
                                <li class="page-item disabled"><span class="page-link">...</span></li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $tasks->url($tasks->lastPage()) }}">{{ $tasks->lastPage() }}</a>
                            </li>
                        @endif

                        {{-- Next --}}
                        @if ($tasks->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $tasks->nextPageUrl() }}">Next</a>
                            </li>
                        @else
                            <li class="page-item disabled"><span class="page-link">Next</span></li>
                        @endif

                    </ul>

                </div>
            </div>
        @endif
    </div>

    {{-- Excel Export Script --}}
    <script src="https://cdn.jsdelivr.net/gh/linways/table-to-excel@v1.0.4/dist/tableToExcel.js"></script>

    <script>
        $(function () {

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


        function applyFilters() {

            var project = $('#projectFilter').val();
            var fromDate = $('#fromDateFilter').val();
            var toDate = $('#toDateFilter').val();

            var baseUrl = window.location.origin + window.location.pathname;

            window.location.href =
                baseUrl +
                '?project=' + project +
                '&fromDate=' + fromDate +
                '&toDate=' + toDate;
        }
    </script>

@endsection