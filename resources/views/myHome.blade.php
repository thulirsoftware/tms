@extends('theme.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Dashboard</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Project Name</th>
            <th>Employee Name</th>
            <th>Total Hours</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            $totalHour = 0;
            $totalMinutes = 0;
        ?>
        @foreach($taskInfos as $taskInfo)
        <?php
            $project = App\Project::find($taskInfo->projectId);
            $employee = App\Employee::find($taskInfo->empId);

            if ($project && $employee) {
                $hours = App\Task::where('projectId', $taskInfo->projectId)
                    ->whereBetween('takenDate', [$weekStartDate, $weekEndDate])
                    ->where('empId', $employee->id)
                    ->sum('hours');
                
                $minutes = App\Task::where('projectId', $taskInfo->projectId)
                    ->whereBetween('takenDate', [$weekStartDate, $weekEndDate])
                    ->where('empId', $employee->id)
                    ->sum('minutes');

                $todayTotalMinutes = $minutes % 60;
                $todayTotalHours = $hours + floor($minutes / 60);
                
                $totalHour = $todayTotalHours;
                $totalMinutes = $todayTotalMinutes;
            }
        ?>
        <tr>
            <td>{{ $project ? $project->projectName : 'Unknown Project' }}</td>
            <td>{{ $employee ? $employee->name : 'Unknown Employee' }}</td>
            <td>{{ $todayTotalHours }} : {{ $todayTotalMinutes }}</td>
        </tr>
        @endforeach
        <?php 
            $employee = App\Employee::where('email', Auth::user()->email)->first();
            $hoursa = App\Task::whereBetween('takenDate', [$weekStartDate, $weekEndDate])
                ->where('empId', $employee ? $employee->id : 0)
                ->sum('hours');
            
            $minutesa = App\Task::whereBetween('takenDate', [$weekStartDate, $weekEndDate])
                ->where('empId', $employee ? $employee->id : 0)
                ->sum('minutes');
            
            $todayTotalMinutesa = $minutesa % 60;
            $todayTotalHoursa = $hoursa + floor($minutesa / 60);
        ?>
        <tr>
            <td></td>
            <td style="text-align:right"><b>Total Hours</b></td>
            <td><b>{{ $todayTotalHoursa }} : {{ $todayTotalMinutesa }}</b></td>
        </tr>
    </tbody>
</table>
@endsection
