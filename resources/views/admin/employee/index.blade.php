@extends('theme.default')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Employees</h1>
        </div>
    </div>

    <div style="text-align: right">
        <a href="{{ route('admin.employee.create') }}" class="btn btn-info btn-circle" title="Add New Employee">
            <i class="fa fa-user-plus"></i>
        </a>
    </div>

    <!-- Employee Table -->
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Mobile</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{$employee->empId}}</td>
                    <td>{{$employee->name}}</td>
                    <td>{{$designations[$employee->designation] ?? '-'}}</td>
                    <td>{{$employee->mobile}}</td>
                    <td onclick="window.location.href='{{ route('admin.employee.edit', ['employee' => $employee->id]) }}'"><i
                            class="fa fa-edit"></i></td>
                    <td>
                        <form action="{{ route('admin.employee.destroy', $employee->id) }}" method="POST"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure?')"
                                style="border:none;background:none;">
                                <i class="fa fa-trash-o"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Intern Table -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Interns</h1>
        </div>
    </div>

    <div style="text-align: right">
        <a href="{{ route('admin.intern.create') }}" class="btn btn-info btn-circle" title="Add New Intern">
            <i class="fa fa-user-plus"></i>
        </a>
    </div>

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Mobile</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($interns) && $interns->count() > 0)
                @foreach($interns as $intern)
                    <tr>
                        <td>{{$intern->employee->empId}}</td>
                        <td>{{$intern->name}}</td>
                        <td>{{$designations[$intern->employee->designation]}}</td>
                        <td>{{$intern->employee->mobile}}</td>
                        <td
                            onclick="window.location.href='{{ route('admin.employee.edit', ['employee' => $intern->employee->id]) }}'">
                            <i class="fa fa-edit"></i>
                        </td>
                        <td>
                            <form action="{{ route('admin.employee.destroy', $intern->employee->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure?')"
                                    style="border:none;background:none;">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

@endsection