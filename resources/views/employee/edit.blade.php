@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Update {{Auth::user()->name}} Information</h1>
      </div>
      <!-- /.col-lg-12 -->
   </div>
   <!-- /.row -->
   <div class="row">
      <div class="col-lg-12">
         <div class="panel panel-default">
            <div class="panel-heading">
               Personel Informations
            </div>
            <div class="panel-body">
               @if ($errors->any())
                  <ul class="alert alert-danger">
                     @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                     @endforeach
                  </ul>
               @endif
               {!! Form::model($employee, [
      'method' => 'POST',
      'url' => ["/Employee/$employee->id/update"],
      'class' => 'form-horizontal',
      'id' => 'employee',
      'files' => true
   ]) !!}
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                        {!! Form::label('name', 'Name `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('name', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('name', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('designation') ? 'has-error' : ''}}">
                        {!! Form::label('designation', 'Designation `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::select('designation', $designation, null, ['class' => 'form-control', 'placeholder' => "Choose One", 'id' => 'designation', 'disabled' => true]) !!}
                           {!! Form::hidden('designation', $employee->designation) !!}
                           {!! $errors->first('designation', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('dob') ? 'has-error' : ''}}">
                        {!! Form::label('dob', 'Date of Birth `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::date('dob', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('dob', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                        {!! Form::label('gender', 'Gender `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::select('gender', ['male' => 'Male', 'female' => 'Female'], null, ['class' => 'form-control', 'required' => true, 'id' => 'gender']) !!}
                           {!! $errors->first('gender', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                        {!! Form::label('email', 'Email `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::email('email', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('email', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('mobile') ? 'has-error' : ''}}">
                        {!! Form::label('mobile', 'Mobile No *', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('mobile', null, [
      'class' => 'form-control',
      'required' => true,
      'maxlength' => 10,
      'pattern' => '[0-9]{10}',
      'title' => 'Enter exactly 10 digits',
      'oninput' => "this.value=this.value.replace(/[^0-9]/g,'')"
   ]) !!}
                           {!! $errors->first('mobile', '<p class="help-block">:message</p>') !!}
                        </div>
                     </div>

                  </div>
               </div>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('motherTongue') ? 'has-error' : ''}}">
                        {!! Form::label('motherTongue', 'Mother Tongue', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('motherTongue', null, ['class' => 'form-control']) !!}
                           {!! $errors->first('motherTongue', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('qualification') ? 'has-error' : ''}}">
                        {!! Form::label('qualification', 'Qualification `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('qualification', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('qualification', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                        {!! Form::label('role_id', 'Role *', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::select('role_id', $roles, $employee->user->roles->first()->id ?? null, [
      'class' => 'form-control',
      'placeholder' => 'Select Role',
      'Disabled' => true,
   ]) !!}
                           {!! Form::hidden('role_id', $employee->user->roles->first()->id ?? null) !!}
                           {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
                        </div>
                     </div>
                  </div>

               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               Communication Address
            </div>
            <div class="panel-body">
               <div class="col-md-12 split">
                  <div class="col-md-4 split">
                     <div class="form-group {{ $errors->has('address') ? 'has-error' : ''}}">
                        {!! Form::label('address', 'Address `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => "Enter Address", 'required' => true]) !!}
                           {!! $errors->first('address', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 split">
                     <div class="form-group {{ $errors->has('city') ? 'has-error' : ''}}">
                        {!! Form::label('city', 'City `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => "Enter City", 'required' => true]) !!}
                           {!! $errors->first('city', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 split">
                     <div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
                        {!! Form::label('state', 'State `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => "Enter State", 'required' => true]) !!}
                           {!! $errors->first('state', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               Experience Information
            </div>
            <div class="panel-body">
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('expLevel') ? 'has-error' : ''}}">
                        {!! Form::label('expLevel', 'Experience/Fresher? `*`', ['class' => 'col-md-5 control-label']) !!}
                        <div class="col-md-5">
                           {!! Form::select('expLevel', ['0' => 'Fresher', '1' => 'Experience', '2' => 'Intern'], null, ['class' => 'form-control', 'required' => true, 'id' => 'expLevel']) !!}
                           {!! $errors->first('expLevel', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('expYear') ? 'has-error' : ''}}">
                        {!! Form::label('expYear', 'Experience ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-4">
                           {!! Form::text('expYear', null, ['class' => 'form-control', 'placeholder' => "Years"]) !!}
                           {!! $errors->first('expYear', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                        <div class="form-group col-md-4 {{ $errors->has('expMonth') ? 'has-error' : ''}}">
                           {!! Form::text('expMonth', null, ['class' => 'form-control', 'placeholder' => "Months"]) !!}
                           {!! $errors->first('expMonth', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               Account Information
            </div>
            <div class="panel-body">
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankAccountName') ? 'has-error' : ''}}">
                        {!! Form::label('bankAccountName', 'AccountHolder Name `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankAccountName', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('bankAccountName', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankAccountNo') ? 'has-error' : ''}}">
                        {!! Form::label('bankAccountNo', 'Account No `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankAccountNo', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('bankAccountNo', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankName') ? 'has-error' : ''}}">
                        {!! Form::label('bankName', 'Bank Name `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankName', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('bankName', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankIfscCode') ? 'has-error' : ''}}">
                        {!! Form::label('bankIfscCode', 'IFSC Code `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankIfscCode', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('bankIfscCode', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
               </div>
               <br>
               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankBranch') ? 'has-error' : ''}}">
                        {!! Form::label('bankBranch', 'Branch Name `*`', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankBranch', null, ['class' => 'form-control', 'required' => true]) !!}
                           {!! $errors->first('bankBranch', '
                                       <p class="help-block">:message</p>
                                       ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="form-group">
         <div class="col-md-9">
         </div>
         <div class=" col-md-1">
            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Save', ['class' => 'btn btn-primary']) !!}
         </div>
      </div>

      {!! Form::close() !!}
   </div>
@endsection