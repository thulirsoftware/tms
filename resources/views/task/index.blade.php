@extends('theme.default')

@section('content')

    <!-- List of Employees -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List of Employees</h1>
        </div>
    </div>

    <table class="table table-striped table-condensed table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Current Project</th>
                <th>Current Activity</th>
                <th>Started From</th>
                <th>Total hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->empId }}</td>
                    <td>{{ $employee->name }}</td>
                    <td>{{ $employee->designate ? $employee->designate->name : 'N/A' }}</td>
                    @php
                        $task = $employeeTasks[$employee->id] ?? null;
                    @endphp
                    @if($task)

                        <td>{{ $task->project->projectName ?? '-' }}</td>
                        <td>{{ $task->activity->name ?? '-'}}</td>
                        <td>{{ date('h:i A', strtotime($task->startTime)) }}</td>
                    @else
                        <td colspan="3" style="text-align: center; color: red;">Currently Not Working</td>
                    @endif
                   <td>{{ $employeeHours[$employee->id] ?? '00h 00m 00s' }}</td>


                    @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                        <td>
                            <a class="btn btn-primary" href="{{ url('Admin/Task/' . $employee->id) }}">View</a>&nbsp;
                            <a class="btn btn-primary" href="{{ url('Admin/Task/create/' . $employee->id) }}">Assign</a>
                        </td>
                    @else
                        <td><a class="btn btn-primary" href="{{ route('teamTaskShow', ['employee' => $employee->id]) }}">View</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- List of Interns -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">List of Interns</h1>
        </div>
    </div>

    <table class="table table-striped table-condensed table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Current Project</th>
                <th>Current Activity</th>
                <th>Started From</th>
                  <th>Total hours</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($interns as $intern)
                <tr>
                    <td>{{ $intern->employee->empId }}</td>
                    <td>{{ $intern->name }}</td>
                    <td>{{ $intern->employee->designate->name }}</td>
                    @php
                        $internTask = $internTasks[$intern->employee->id] ?? null;
                    @endphp
                    @if($internTask)
                        <td>{{ $internTask->project->projectName ?? 'no project' }}</td>
                        <td>{{ $internTask->activity->name }}</td>
                        <td>{{ date('h:i A', strtotime($internTask->startTime)) }}</td>
                    @else
                        <td colspan="3" style="text-align: center; color: red;">Currently Not Working</td>
                    @endif
                    <td>{{ $employeeHours[$employee->id] ?? '00h 00m 00s' }}</td>
                    @if(Auth::user()->type == 'admin' || Auth::user()->hasPermission('Tasks'))
                        <td>
                            <a class="btn btn-primary" href="{{ url('Admin/Task/' . $intern->employee->id) }}">View</a>&nbsp;
                            <a class="btn btn-primary" href="{{ url('Admin/Task/create/' . $intern->employee->id) }}">Assign</a>
                        </td>
                    @else
                        <td><a class="btn btn-primary"
                                href="{{ route('teamTaskShow', ['employee' => $intern->employee->id]) }}">View</a></td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection