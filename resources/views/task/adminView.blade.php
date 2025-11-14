@extends('theme.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 well">
            <div class="col-md-2">
                @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                    <div class="form-group">
                        <label>Employees</label>
                        <select class="form-control" id="employeeFilter" onchange="viewEmployee()">
                            <option value="">Select one</option>
                            <?php    $employees = App\Employee::all();?>
                            @foreach($employees as $key => $emp)
                                <option value="{{$emp->id}}" <?=($emp->id == $employee->id) ? 'selected' : ''?>>{{$emp->name}}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <div class="form-group">
                        <label>Employees</label>
                        <select class="form-control" id="employeeFilter" onchange="teamViewEmployee()">
                            <option value="">Select one</option>
                            <?php    $employees = App\Employee::all(); ?>
                            @foreach($employees as $key => $emp)
                                <option value="{{ $emp->id }}" {{ $emp->id == $employee->id ? 'selected' : '' }}>{{ $emp->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="col-md-6">
                <br>
                <a href="#"><button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal"><i
                            class="fa fa-pie-chart" aria-hidden="true"></i>
                        Chart
                    </button></a>
                @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                    <a href="{{url('/Task')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded"><i class="fa fa-plus"
                            aria-hidden="true"></i> New Task</a>
                    <a href="{{url('/RegularTask')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded"><i
                            class="fa fa-plus" aria-hidden="true"></i> Regular Task</a>
                @endif
            </div>
        </div>

        <div class="col-lg-12 well">
            @if($currentTasks->count() > 0)
                <h3>Current Task</h3>
                <table class="table table-striped table-bordered table-hover table-condensed " id='currentTasksTable'>
                    <thead>
                        <tr>
                            <th>As.Date</th>
                            <th>Project</th>
                            <th>Activity</th>
                            <th>Instruction</th>
                            <th>Start Time</th>
                            @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                                <th>Comment</th>
                                <th>Current Status</th>
                                <th>Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($currentTasks as $task)
                            <tr>
                                <td>{{date('M-d', strtotime($task->assignedDate))}}</td>
                                <td>{{$task->project->projectName ?? '-'}}
                                    @if($task->priority != null)
                                        <sup><i
                                                class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                    @endif
                                </td>
                                <td>{{$task->activity->name}}</td>
                                <td>
                                    <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                        title="{{$task->instruction}}">
                                        {{(strlen($task->instruction) > 20) ? substr($task->instruction, 0, 16) . ' ...' : $task->instruction}}
                                    </p>
                                </td>
                                <td>{{date('h:i A', strtotime($task->startTime))}}</td>
                                @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                                    <td><input class="form-control" type="text" id="taskComment" name="taskComment"><span
                                            class="alert-danger" style="text-align:center;display: none;">Please give Comment</span>
                                    </td>
                                    <td>
                                        <select id="taskStatus" class="form-control">
                                            <option value="">Choose Status</option>
                                            @foreach($taskStatus as $key => $status)
                                                @if($key > 2)
                                                    <option value="{{$key}}">{{$status}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="alert-danger" style="text-align:center;display: none;">Choose status!</span>
                                    </td>
                                    <td>
                                        @if($task->status == 2)
                                            <i id="stopTimer<?=$task->id?>" onclick="stopTime({{$task->id}})" class="fa fa-hourglass-end"
                                                style="font-size:25px;color:red"></i>
                                            &nbsp;
                                        @else
                                            <button id="startTimer<?=$task->id?>" class="btn btn-success"
                                                onclick="startTime({{$task->id}})">Start</button>
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <h4 style="text-align: center;color: red">Currently not Working!</h4>
            @endif
        </div>

        <div class="col-lg-12 well">
            @if($completedTasks->count() != null)
                    <h3>Completed Tasks</h3>
                    <table class="table table-striped table-bordered table-condensed  table-hover">
                        <thead>
                            <tr>
                                <!-- <th>Task ID</th> -->
                                <th>As.Date</th>
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
                $sumMinutes = 0;
                    ?>
                            @foreach($completedTasks as $key => $task)

                                        <tr class="clicker">

                                            <!-- <td>{{$task->id}} || {{$task->relatedTaskId}} </td> -->
                                            <td>{{date('M-d', strtotime($task->assignedDate))}}</td>
                                            <td>{{$task->project->projectName ?? '-'}}
                                                @if($task->priority != null)
                                                    <sup><i
                                                            class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                                @endif
                                            </td>
                                            <td>{{$task->activity->name}}</td>
                                            <td>
                                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                                    title="{{$task->instruction}}">
                                                    {{(strlen($task->instruction) > 20) ? substr($task->instruction, 0, 16) . ' ...' : $task->instruction}}
                                                </p>
                                            </td>
                                            <td>
                                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip" title="{{$task->comment}}">
                                                    {{(strlen($task->comment) > 20) ? substr($task->comment, 0, 16) . ' ...' : $task->comment}}</p>
                                            </td>
                                            <td>{{date('h:i A', strtotime($task->startTime))}}</td>
                                            <td>{{date('h:i A', strtotime($task->endTime))}}</td>
                                            <td>{{$task->hours}}:{{$task->minutes}}</td>
                                            <td>{{$task->state->name}}
                                                @if($task->id == $task->relatedTaskId && isset($task[$key]['flag']))
                                                    [ {{$task[$key]['flag']}} ]
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
                                if (count($completedTasks) == $key + 1) {
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

                            <th colspan="02" style="text-align: right;">
                                Today Lunch Hours:
                            </th>
                            <th style="color: red">
                                {{str_pad($todayLunchHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayLunchMinutes, 2, "0", STR_PAD_LEFT)}}
                            </th>
                            <th colspan="02" style="text-align: right;">
                                Today Break Hours:
                            </th>
                            <th style="color: red">
                                {{str_pad($todayBreakHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayBreakMinutes, 2, "0", STR_PAD_LEFT)}}
                            </th>
                            <th colspan="02" style="text-align: right;">
                                Today Work Hours:
                            </th>
                            <th style="color: red">
                                {{str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT)}}
                            </th>
                        </tr>
                    </table>
            @else
                <h4 style="text-align: center;color: red">Completed tasks are not available! </h4>
            @endif

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Total Working Hours</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            @if($uniqueTasks != 0)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Working Percentage</h3>
                                    </div>
                                    <div class="panel-body" align="center">
                                        <div id="pie_chart" style="width:650px;height: 500px;">

                                        </div>
                                    </div>
                                </div>
                            @endif



                            @if($uniqueTasks != 0)
                                <tr>
                                    <table class="table table-striped table-bordered table-condensed  table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="{{(Auth::user()->type != 'admin') ? 9 : 9}}" class="table-heading">Total
                                                    Hours</th>
                                            </tr>
                                            <tr>
                                                <th>As.Date</th>
                                                <th>Project</th>
                                                <th>Activity</th>
                                                <th>Instruction</th>
                                                <th>Comment</th>
                                                <th>HH:MM</th>
                                                <th>Current Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($uniqueTasks as $key => $task)

                                                <tr>
                                                    <td>{{date('M-d', strtotime($task['assignedDate']))}}</td>

                                                    <td>{{$task['projectName']}}

                                                        @if($task['priority'] != null)
                                                            <sup><i
                                                                    class="fa fa-flag {{($task['priority'] != 0) ? (($task['priority'] == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                                        @endif
                                                    </td>

                                                    <td>{{$task['taskActivity']}}</td>

                                                    <td>
                                                        <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                                            title="{{$task['instruction']}}">
                                                            {{(strlen($task['instruction']) > 20) ? substr($task['instruction'], 0, 16) . ' ...' : $task['instruction']}}
                                                        </p>
                                                    </td>

                                                    <td>
                                                        <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                                            title="{{$task['comment']}}">
                                                            {{(strlen($task['comment']) > 20) ? substr($task['comment'], 0, 16) . ' ...' : $task['comment']}}
                                                        </p>
                                                    </td>

                                                    <td>{{str_pad($task['todayTotalHours'], 2, "0", STR_PAD_LEFT)}}:{{str_pad($task['todayTotalMinutes'], 2, "0", STR_PAD_LEFT)}}
                                                    </td>

                                                    <td>{{$task['taskStatus']}}</td>
                                                </tr>
                                                <tr></tr>
                                            @endforeach
                                            <?    $i = 1; ?>
                                            @foreach($uniqueTasks as $key => $task)
                                                {{-- Task row here --}}

                                                @if ($loop->first)
                                                    <tr>
                                                        <td colspan="4"></td>
                                                        <td colspan="1" style="font-weight:bold; text-align:right;">Today Work Hours:
                                                        </td>
                                                        <td style="color:red; font-weight:bold;">
                                                            {{ str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT) }}:{{ str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT) }}
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </tr>
                            @endif


                        </div>
                    </div>
                </div>
            </div>





        </div>
        <div class="col-lg-12 well">
            <h3>Regular Tasks</h3>
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th>As.Date</th>
                        <th>Project</th>
                        <th>Activity</th>
                        <th>Instruction</th>
                        <th>Current Status</th>
                        @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($regularTasks as $task)
                                        <tr>

                                            <td>{{date('M-d', strtotime($task->assignedDate))}}

                                                <sup class="badge {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"
                                                    style="background-color: white">{{substr($taskTypes[$task->taskType], 0, 1)}}</sup>
                                            </td>
                                            <td>{{$task->project->projectName ?? '-'}}
                                                @if($task->priority != null)
                                                    <sup><i
                                                            class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                                @endif
                                            </td>
                                            <td>{{$task->activity->name}}</td>
                                            <td>
                                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                                    title="{{$task->instruction}}">
                                                    {{(strlen($task->instruction) > 20) ? substr($task->instruction, 0, 16) . ' ...' : $task->instruction}}
                                                </p>
                                            </td>
                                            <td>
                                                <?php
                            if ($task['taskType'] == 0 && $task['takenDate'] == date('Y-m-d')) {
                                echo 'Last taken @ ' . date('M-d', strtotime($task['takenDate']));
                            } elseif ($task['taskType'] == 1 && ($task['takenDate'] > date('Y-m-d', strtotime('1 week ago')))) {
                                echo 'Last taken @ ' . date('M-d', strtotime($task['takenDate']));
                            } elseif ($task['taskType'] == 2 && ($task['takenDate'] > date('Y-m-d', strtotime('1 month ago')))) {
                                echo 'Last taken @ ' . date('M-d', strtotime($task['takenDate']));
                            } else {
                                echo 'Not Taken';
                            }

                        ?>
                                            </td>

                                            @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                                                <td>
                                                    @if(Auth::user()->type != 'admin')
                                                        @if($task->approval == 'yes')
                                                            <i id="startTimer<?=$task->id?>" onclick="startTime({{$task->id}})"
                                                                class="fa fa-hourglass-start" style="font-size:25px;color:green"></i>
                                                            &nbsp;
                                                        @else
                                                            <i id="approveTask<?=$task->id?>" class="fa fa-thumbs-down"
                                                                style="font-size:25px;color:pink"></i>
                                                        @endif
                                                    @else
                                                        @if($task->approval == 'no')
                                                            <i id="declineTask<?=$task->id?>" onclick="approveTask({{$task->id}},'regular')"
                                                                class="fa fa-thumbs-down" style="font-size:25px;color:pink"></i>
                                                        @else
                                                            <i id="approveTask<?=$task->id?>" onclick="declineTask({{$task->id}},'regular')"
                                                                class="fa fa-thumbs-up" style="font-size:25px;color:green"></i>
                                                        @endif
                                                    @endif

                                                    @if(Auth::user()->id == $task->assignedBy || Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                                                    
                                                        <a href="{{url('/RegularTask')}}/{{$task->id}}/edit"><i class="fa fa-edit"
                                                                style="font-size:20px;color:blue"></i></a>&nbsp;
                                                        <a href="{{url('/RegularTask')}}/{{$task->id}}/destroy"><i class="fa fa-trash"
                                                                style="font-size:20px;color:red"></i></a>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-lg-12 well">
            <h3>Assigned / Pending Tasks</h3>
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th>As.Date</th>
                        <th>Project</th>
                        <th>Activity</th>
                        <th>Instruction</th>
                        <th>Current Status</th>
                        @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedTasks as $task)
                        <tr>
                            <!-- <td>{{$task->id}} || {{$task->relatedTaskId}} </td> -->
                            <td>{{date('M-d', strtotime($task->assignedDate))}}</td>
                            <td>{{$task->project->projectName ?? '-'}}
                                @if($task->priority != null)
                                    <sup><i
                                            class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                                @endif
                            </td>
                            <td>{{$task->activity->name}}</td>
                            <td>
                                <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                    title="{{$task->instruction}}">
                                    {{(strlen($task->instruction) > 20) ? substr($task->instruction, 0, 16) . ' ...' : $task->instruction}}
                                </p>
                            </td>
                            <td>{{$task->state->name}}</td>

                            @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                                <td>
                                    @if(Auth::user()->type == 'admin')
                                        @if($task->approval == 'yes')
                                            <i id="startTimer<?=$task->id?>" onclick="startTime({{$task->id}})"
                                                class="fa fa-hourglass-start" style="font-size:25px;color:green"></i>
                                            &nbsp;
                                        @else
                                            <i id="approveTask<?=$task->id?>" class="fa fa-thumbs-down"
                                                style="font-size:25px;color:pink"></i>
                                        @endif
                                    @else
                                        @if($task->approval == 'no')
                                            <i id="declineTask<?=$task->id?>" onclick="approveTask({{$task->id}})" class="fa fa-thumbs-up"
                                                style="font-size:25px;color:green"></i>
                                        @else
                                            <i id="approveTask<?=$task->id?>" onclick="declineTask({{$task->id}})" class="fa fa-thumbs-down"
                                                style="font-size:25px;color:pink"></i>
                                        @endif
                                    @endif

                                    @if(Auth::user()->id == $task->assignedBy || Auth::user()->type == 'admin')

                                        <a href="{{url('/Task')}}/{{$task->id}}/edit"><i class="fa fa-edit"
                                                style="font-size:20px;color:blue"></i></a>&nbsp;
                                        <!-- <a href="{{url('/Task')}}/{{$task->id}}/destroy"><i class="fa fa-trash"
                                                style="font-size:20px;color:red"></i></a> -->
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="display: flex; justify-content: center; align-items: center; height: 100px;">
                @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                    <a class="btn btn-info btn-rounded" href="{{ url('/Admin/Task') }}">
                        Back
                    </a>
                @else
                    <a class="btn btn-info btn-rounded" href="{{ route('teamTask') }}">
                        Back
                    </a>
                @endif
            </div>

        </div>






        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />-->


        <script type="text/javascript">
            var analytics = <?php echo $gender; ?>

            google.charts.load('current', { 'packages': ['corechart'] });

            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable(analytics);
                var options = {
                    title: 'Working Hours Percentage',
                    'width': 700,
                    'height': 500
                };
                var chart = new google.visualization.PieChart(document.getElementById('pie_chart'));
                chart.draw(data, options);
            }
        </script>













        <script type="text/javascript">
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });
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
            function startTime(taskId) {
                var time = getTime();

                if ($('#currentTasksTable tr').length > 1) {
                    $('.alert-danger').css("display", "none");
                    if (!$('#taskComment').val()) {
                        valid = false;
                        $("#taskComment").focus();
                        $('.alert-danger').css("display", "block");
                        // insertAfter('#taskComment');
                    }
                    if (!$('#taskStatus').val()) {
                        valid = false;
                        $("#taskStatus").focus();
                        $('.alert-danger').css("display", "block");
                    }
                    return false;
                    exit();
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('SetStartTime') }}",
                    data: { taskId: taskId, time: time }
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
            function stopTime(taskId) {
                var time = getTime();
                var valid = true;
                $('span.error', this).remove();
                if (!$('#taskComment').val()) {
                    valid = false;
                    $("#taskComment").focus();
                    $('<span class="alert-danger" style="text-align:center;" >Please give Comment</span>').
                        insertAfter('#taskComment');
                }
                if (!$('#taskStatus').val()) {
                    valid = false;
                    $("#taskStatus").focus();
                    $('<span class="alert-danger" style="text-align:center;" >Choose status!</span>').
                        insertAfter('#taskStatus');
                }
                var taskComment = $("#taskComment").val();
                var taskStatus = $("#taskStatus").find('option:selected').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('SetEndTime') }}",
                    data: { taskId: taskId, time: time, taskStatus: taskStatus, taskComment: taskComment }
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
            function approveTask(taskId, type = '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ApproveTask') }}",
                    data: { taskId: taskId, type: type }
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

            function declineTask(taskId, type = '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('DeclineTask') }}",
                    data: { taskId: taskId, type: type }
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

            function setInterrupt(interruptFor) {
                var time = getTime();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('SetInterrupt') }}",
                    data: { interruptFor: interruptFor, time: time }
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

            function viewEmployee() {
                var empId = $("#employeeFilter option:selected").val();
                window.location.href = "{{URL::to('/Admin/Task')}}/" + empId;


            }
            function teamViewEmployee() {
                var empId = $("#employeeFilter option:selected").val();
                var baseUrl = "{{ route('teamTaskShow', ['employee' => 'EMPLOYEE_ID']) }}";
                var url = baseUrl.replace('EMPLOYEE_ID', empId);
                window.location.href = url;
            }
        </script>
@endsection