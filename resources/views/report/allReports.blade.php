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
        <table  class="table table-striped table-bordered table-condensed  table-hover" id="biometric_reports_valid">
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
                    <th>Current Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                                        $todayLunchHours = 0;
$todayLunchMinutes = 0;
$sumLunchMins = 0;
$todayBreakHours = 0;
$todayBreakMinutes = 0;
$sumBreakMins = 0;
$todayTotalHours = 0;
$todayTotalMinutes = 0;
$sumMinutes = 0;?>
                @foreach($tasks as $key => $task)
                                <tr>

                                    <td>{{date('M-d', strtotime($task->assignedDate))}}</td>
                                    <td>{{date('M-d', strtotime($task->takenDate))}}</td>
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
                                <?php 
                                                                                                                                                                                            if ($task->activityId == '1')//For Lunch Time
                    {
                        $todayLunchHours += $task->hours;
                        $sumLunchMins += $task->minutes;
                    }
                    if ($task->activityId == '3')//For Break Time
                    {
                        $todayBreakHours += $task->hours;
                        $sumBreakMins += $task->minutes;
                    }
                    if (!in_array($task->activityId, [1, 3]))//For Work Time
                    {
                        $todayTotalHours += $task->hours;
                        $sumMinutes += $task->minutes;
                    }
                    if (count($tasks) == $key + 1) {
                        if ($sumMinutes > 0) {

                            $todayTotalHours += floor($sumMinutes / 60);
                            $todayTotalMinutes = floor($sumMinutes % 60);

                        } else {
                            $todayTotalMinutes = $sumMinutes;
                        }

                        if ($sumLunchMins > 0) {

                            $todayLunchHours += floor($sumLunchMins / 60);
                            $todayLunchMinutes = floor($sumLunchMins % 60);

                        } else {
                            $todayLunchMinutes = $sumLunchMins;
                        }

                        if ($sumBreakMins > 0) {

                            $todayBreakHours += floor($sumBreakMins / 60);
                            $todayBreakMinutes = floor($sumBreakMins % 60);

                        } else {
                            $todayBreakMinutes = $sumBreakMins;
                        }
                    }
                                                                                                                                                                                            ?>
                @endforeach

            </tbody>
            <tr>

                <th colspan="03" style="text-align: right;">
                    Lunch Hours:
                </th>
                <th style="color: red">
                    {{str_pad($todayLunchHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayLunchMinutes, 2, "0", STR_PAD_LEFT)}}
                </th>
                <th colspan="02" style="text-align: right;">
                    Break Hours:
                </th>
                <th style="color: red">
                    {{str_pad($todayBreakHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayBreakMinutes, 2, "0", STR_PAD_LEFT)}}
                </th>
                <th colspan="02" style="text-align: right;">
                    Work Hours:
                </th>
                <th style="color: red">
                    {{str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT)}}
                </th>
            </tr>
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