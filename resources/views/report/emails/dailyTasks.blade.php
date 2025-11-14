<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{!! asset('theme/vendor/bootstrap/css/bootstrap.min.css') !!}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{!! asset('theme/vendor/metisMenu/metisMenu.min.css') !!}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{!! asset('theme/dist/css/sb-admin-2.css') !!}" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{!! asset('theme/vendor/morrisjs/morris.css') !!}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{!! asset('theme/vendor/font-awesome/css/font-awesome.min.css') !!}" rel="stylesheet" type="text/css">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<style type="text/css">
    .manditary {
        color: red;
    }

    .supHigh {
        color: red;
    }

    .supMedium {
        color: green;
    }

    .supLow {
        color: blue;
    }

    .red-tooltip+.tooltip>.tooltip-inner {
        background-color: lightgreen;
        color: black;
        font-size: 15px
    }

    .red-tooltip+.tooltip>.tooltip-arrow {
        border-bottom-color: #f00;
    }

    .fa {
        cursor: pointer;
    }

    .well {
        min-height: 0px;
        padding: 5px 15px 0px 15px;
        background-color: #f5f5f5;
        margin-top: 5px;
        margin-bottom: 10px;
    }
</style>

<body>
    <table style="width: 100%;" border="0" cellspacing="3" cellpadding="2" align="left">
        <tbody>
            <tr>
                <table class="table table-striped table-bordered table-condensed  table-hover" border="2px">
                    <thead>
                        <tr>
                            <th>Employee</th>
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
                        <?php $todayTotalHours = 0;
$todayTotalMinutes = 0;
$sumMinutes = 0;?>
                        @foreach($tasks as $key => $task)
                                                <tr>
                                                    <td>{{$employees[$task->empId]}} </td>
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
                                                        <p data-toggle="tooltip" data-placement="top" class="red-tooltip"
                                                            title="{{$task->comment}}">
                                                            {{(strlen($task->comment) > 20) ? substr($task->comment, 0, 16) . ' ...' : $task->comment}}
                                                        </p>
                                                    </td>
                                                    <td>{{date('h:i A', strtotime($task->startTime))}}</td>
                                                    <td>{{date('h:i A', strtotime($task->endTime))}}</td>
                                                    <td>{{$task->hours}}:{{$task->minutes}}</td>
                                                    <td>{{$task->state->name}}
                                                        @if($task->id == $task->relatedTaskId && isset($tasks[$key]['flag']))
                                                            [ {{$tasks[$key]['flag']}} ]
                                                        @endif
                                                    </td>
                                                </tr>
                                                <?php 
                                    $todayTotalHours += $task->hours;
                            $sumMinutes += $task->minutes;
                            if (count($tasks) == $key + 1) {
                                if ($sumMinutes > 0) {

                                    $todayTotalHours += floor($sumMinutes / 60);
                                    $todayTotalMinutes = floor($sumMinutes % 60);

                                } else {
                                    $todayTotalMinutes = $sumMinutes;
                                }

                            }
                                    ?>
                                                @if(isset($tasks[$key + 1]->empId))
                                                    @if($tasks[$key + 1]->empId != $task->empId)

                                                                    <tr>

                                                                        <th colspan="08" style="text-align: right;">
                                                                            Today Worked Hours:
                                                                        </th>
                                                                        <th width="80px">
                                                                            <input type="text" class="form-control"
                                                                                value='{{str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT)}}'
                                                                                disabled="">
                                                                        </th>
                                                                        <th></th>
                                                                    </tr>
                                                                    <?php            $todayTotalHours = 0;
                                                        $todayTotalMinutes = 0;
                                                        $sumMinutes = 0;?>
                                                    @endif
                                                @else
                                                    <tr>

                                                        <th colspan="08" style="text-align: right;">
                                                            Today Worked Hours:
                                                        </th>
                                                        <th width="80px">
                                                            <input type="text" class="form-control"
                                                                value='{{str_pad($todayTotalHours, 2, "0", STR_PAD_LEFT)}}:{{str_pad($todayTotalMinutes, 2, "0", STR_PAD_LEFT)}}'
                                                                disabled="">
                                                        </th>
                                                        <th></th>
                                                    </tr>
                                                @endif
                        @endforeach

                    </tbody>
                </table>
            </tr>
        </tbody>
    </table>
</body>

</html>