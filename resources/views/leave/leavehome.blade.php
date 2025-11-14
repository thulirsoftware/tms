@extends('theme.default')

@section('content')
    <div class="row">
        <a href="/Admin/Leave"><i class="fa fa-arrow-left fa-2x" aria-hidden="true"></i>
        </a>
        <div class="col-lg-12">
            <h1 class="page-header">List of Employees Leave Report</h1>
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{$employee->empId}}</td>
                    <td>{{$employee->name}} </td>
                    <td>
                        @if(isset($designations[$employee->designation]))
                            {{ $designations[$employee->designation] }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td><a class="btn btn-primary" href="{{url('Admin/Leave')}}/{{$employee->id}}/show">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection