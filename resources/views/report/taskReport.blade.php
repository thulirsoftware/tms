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
                <tbody>
                    @php
                        $groupedTasks = $tasks->getCollection()->groupBy(function ($task) {
                            return date('Y-m-d', strtotime($task->takenDate));
                        });
                    @endphp
                    @foreach($groupedTasks as $date => $dayTasks)

                        @php
                            $rowCount = count($dayTasks);
                        @endphp
                        @foreach($dayTasks as $key => $task)
                            <tr>

                                <td>{{date('M-d', strtotime($task->assignedDate))}}</td>
                                @if($key == 0)
                                    <td rowspan="{{ $rowCount }}" class="align-top fw-bold" style="vertical-align: middle;">
                                        {{ date('M d', strtotime($date)) }}
                                    </td>
                                @endif
                                <td>
                                    @if($task->employee)
                                        {{ $task->employee->name ?? '-' }}
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                @if($task->project !== null)
                                    <td>{{$task->project->projectName ?? '-'}}
                                        @if($task->priority != null)
                                            <sup><i
                                                    class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                        @endif
                                    </td>
                                @else
                                    <td></td>
                                @endif
                                <td>{{$task->activity->name}}</td>
                                <td>
                                    <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                        title="{{$task->instruction}}">
                                        {{(strlen($task->instruction) > 20) ? substr($task->instruction, 0, 16) . ' ...' : $task->instruction}}
                                    </p>
                                </td>
                                <td>
                                    <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$task->comment}}">
                                        {{(strlen($task->comment) > 20) ? substr($task->comment, 0, 16) . ' ...' : $task->comment}}
                                    </p>
                                </td>
                                <td>{{date('h:i A', strtotime($task->startTime))}}</td>
                                <td>{{date('h:i A', strtotime(($task->endTime != '') ? $task->endTime : (date('H:i:s'))))}}</td>
                                @if($task->hours != null && $task->minutes != null && $task->endTime != null)
                                    <td>{{$task->hours}}:{{$task->minutes}}</td>
                                @else
                                            <?php 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            $etime = explode(':', date('H:i:s'));
                                    $stime = explode(':', date('H:i:s', strtotime($task->startTime)));
                                    $allMinutes = (($etime[0] * 60) + $etime[1]) - (($stime[0] * 60) + $stime[1]);
                                    $task->hours = str_pad(intval($allMinutes / 60), 2, "0", STR_PAD_LEFT);
                                    $task->minutes = str_pad(intval($allMinutes % 60), 2, "0", STR_PAD_LEFT); 
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            ?>
                                            <td>{{$task->hours}}:{{$task->minutes}}</td>
                                @endif
                                @if($key == 0)

                                    <td class="fw-bold text-primary" rowspan="{{ $rowCount }}" style="vertical-align: middle;">
                                        {{ $dailyTotals[$date]['hours'] ?? 0 }} hours
                                        {{ str_pad($dailyTotals[$date]['minutes'] ?? 0, 2, '0', STR_PAD_LEFT) }} minutes
                                    </td>
                                @endif



                                <td>

                                    @if(isset($task->state) && isset($task->state->name))
                                        {{$task->state->name}}
                                    @else
                                        <span>State name not available</span>
                                    @endif

                                    @if($task->id == $task->relatedTaskId && isset($tasks[$key]['flag']))
                                        [ {{$tasks[$key]['flag']}} ]
                                    @else
                                        <!-- This is the negative case, where the condition is not met -->
                                        <span>No related task flag</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                    @endforeach
                </tbody>
                <tr>

                    <th colspan="03" style="text-align: right;">
                        Lunch Hours:
                    </th>
                    <th style="color: red">
                        {{ str_pad($summary['lunch_hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($summary['lunch_minutes'], 2, '0', STR_PAD_LEFT) }}
                    </th>
                    <th colspan="02" style="text-align: right;">
                        Break Hours:
                    </th>
                    <th style="color: red">
                        {{ str_pad($summary['break_hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($summary['break_minutes'], 2, '0', STR_PAD_LEFT) }}
                    </th>
                    <th colspan="02" style="text-align: right;">
                        Work Hours:
                    </th>
                    <th style="color: red">
                        {{ str_pad($summary['work_hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($summary['work_minutes'], 2, '0', STR_PAD_LEFT) }}
                    </th>
                </tr>
            </table>
            @if ($tasks->hasPages())
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

                            {{-- Previous --}}
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

            </div> @endif

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