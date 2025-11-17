@extends('theme.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 well">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Employees</label>
                    <select class="form-control" id="employeeFilter" onchange="viewEmployee()">
                        <option value="">Select one</option>
                        <?php $employees = App\Employee::all();?>
                        @foreach($employees as $key => $emp)
                            <option value="{{$emp->id}}" <?=($emp->id == $employee->id) ? 'selected' : ''?>>{{$emp->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <br>
                <a href="{{url('/Task')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded"><i class="fa fa-plus"
                        aria-hidden="true"></i> New Task</a>
                <a href="{{url('/RegularTask')}}/{{$employee->id}}/create" class="btn btn-info btn-rounded"><i
                        class="fa fa-plus" aria-hidden="true"></i> Regular Task</a>
            </div>
        </div>

        <div class="col-lg-12 well">
            @if($currentTasks->count() != null)
                <h3>Current Task</h3>
                <table class="table table-striped table-bordered table-hover table-condensed " id='currentTasksTable'>
                    <thead>
                        <tr>
                            <th>As.Date</th>
                            <th>Project</th>
                            <th>Activity</th>
                            <th>Instruction</th>
                            <th>Start Time</th>
                            @if(Auth::user()->type != 'admin')
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
                                @if(Auth::user()->type != 'admin')
                                    <td><input class="form-control" type="text" id="taskComment" name="taskComment"><span
                                            class="alert-danger" style="text-align:center;display: none;">Please give Comment</span>
                                    </td>
                                    <td>
                                        {{-- <select id="taskStatus" class="form-control">
                                            <option value="">Choose Status</option>
                                            @foreach($taskStatus as $key => $status)
                                            @if($key>2)
                                            <option value="{{$key}}">{{$status}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        --}}
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
                            <?php    $todayTotalHours = 0;
                $todayTotalMinutes = 0;
                $sumMinutes = 0;?>
                            @foreach($completedTasks as $key => $task)

                                        @if($task->id == $task->relatedTaskId || $task->assignedDate != $task->takenDate)
                                            <tr class="clicker">
                                        @else
                                                <tr class="shower" style="display: none;">
                                            @endif
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
                                                @if($task->id == $task->relatedTaskId && isset($completedTasks[$key]['flag']))
                                                    [ {{$completedTasks[$key]['flag']}} ]
                                                @endif
                                            </td>
                                        </tr>
                                        <?php 
                                    $todayTotalHours += $task->hours;
                                $sumMinutes += $task->minutes;
                                if (count($completedTasks) == $key + 1) {
                                    if ($sumMinutes > 0) {

                                        $todayTotalHours += floor($sumMinutes / 60);
                                        $todayTotalMinutes = floor($sumMinutes % 60);

                                    } else {
                                        $todayTotalMinutes = $sumMinutes;
                                    }

                                }
                                    ?>
                            @endforeach

                        </tbody>
                        <tr>

                            <th colspan="07" style="text-align: right;">
                                Today Worked Hours:
                            </th>
                            <th>{{str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT)}}
                            </th>
                            <th></th>
                        </tr>
                    </table>
            @else
                <h4 style="text-align: center;color: red">Completed tasks are not available! </h4>
            @endif
        </div>
        <div class="col-lg-12 well">
            <h3>Assigned / Pending Tasks</h3>
            <table class="table table-striped table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th>Task ID</th>
                        <th>As.Date</th>
                        <th>Project</th>
                        <th>Activity</th>
                        <th>Instruction</th>
                        <th>Current Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignedTasks as $task)
                        <tr>
                            <td>{{$task->id}} || {{$task->relatedTaskId}} </td>
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
                                    <a href="{{url('/Task')}}/{{$task->id}}/destroy"><i class="fa fa-trash"
                                            style="font-size:20px;color:red"></i></a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
            function approveTask(taskId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ApproveTask') }}",
                    data: { taskId: taskId }
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

            function declineTask(taskId) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': '{{csrf_token()}}'
                    }
                });

                $.ajax({
                    method: "POST",
                    url: "{{ route('ApproveTask') }}",
                    data: { taskId: taskId }
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

            function viewEmployee() {
                var empId = $("#employeeFilter option:selected").val();
                window.location.href = "{{URL::to('/Admin/Task')}}/" + empId;


            }
        </script>
@endsection