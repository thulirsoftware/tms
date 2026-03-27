@extends('theme.default')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Employees</h1>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div style="text-align: right">
<a href="{{url('/Employee/')}}/{{$employee->id}}/edit-employee" class="btn btn-info btn-circle"><i class="fa fa-edit"></i></a>
</div>
<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Mobile</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{$employee['empId']}}</td>
            <td>{{$employee['name']}}</td>
            <td>{{$designations[$employee['designation']]}}</td>
            <td>{{$employee['mobile']}}</td>
            
        </tr>
    </tbody>
</table>

@endsection