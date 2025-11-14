<table class="table table-striped table-bordered table-condensed  table-hover">
    <thead>
        <tr>
            <th>Taken Date</th>
            <th>Project</th>
            <th>Employer Name</th>

            <th>Activity</th>
            <th>Total Hours</th>
        </tr>
    </thead>
    <tbody>

        @foreach($tasks as $key => $task)
                <tr>

                    <td>{{date('M-d', strtotime($task->takenDate))}}</td>
                    <?php 
                         $employee = App\Employee::where('id', $task->empId)->first();

            $hours = App\Task::where('projectId', $task->projectId)->where('empId', $task->empId)->where('activityId', $task->activityId)->where('takenDate', $task->takenDate)->sum('hours');
            $minutes = App\Task::where('projectId', $task->projectId)->where('empId', $task->empId)->where('activityId', $task->activityId)->where('takenDate', $task->takenDate)->sum('minutes');

            $todayTotalMinutes = floor($minutes % 60);
            $todayTotalHours = $hours;
            if ($minutes > 60) {
                $todayTotalHours = $hours + floor($minutes / 60);
            }

                         ?>
                    @if($task->project != null)
                        <td>{{$task->project->projectName ?? '-'}}
                            @if($task->priority != null)
                                <sup><i
                                        class="fa fa-flag {{($task->priority != 0) ? (($task->priority == 1) ? ('supMedium') : 'supLow') : 'supHigh'}}"></i></sup>
                            @endif
                        </td>
                    @else
                        <td></td>
                    @endif
                    @if($employee != null)
                        <td>{{$employee->name}}</td>
                    @else
                        <td></td>
                    @endif
                    @if($task->activity != null)
                        <td>{{$task->activity->name}}</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$todayTotalHours}} : {{$todayTotalMinutes}}</td>

                </tr>

        @endforeach

    </tbody>

</table>