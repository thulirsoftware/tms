@extends('theme.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">List of Employees</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Current Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach($employees as $employee)
        <tr>
            <td>{{$employee->empId}}</td>
            <td>{{$employee->name}} </td>
            <td>{{$designations[$employee->designation]}}</td>
            @if(isset($currentTasks[$employee->id]))
            <td>{{$currentTasks[$employee->id]['instruction']}} @ {{$activities[$currentTasks[$employee->id]['activityId']]}} @ {{$projects[$currentTasks[$employee->id]['projectId']]}} From {{$currentTasks[$employee->id]['startTime']}}</td>
            @else
            <td>Currently Not Working</td>
            @endif
            <td><a class="btn btn-primary" href="{{url('Admin/Task')}}/{{$employee->id}}">View</a>&nbsp;<a class="btn btn-primary" href="{{url('Admin/Task/create')}}/{{$employee->id}}">Assign</a></td>
        </tr>
    @endforeach
    </tbody>
</table>

@endsection