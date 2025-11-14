@extends('theme.default')
@section('content')
   <div class="row">
      <div class="col-lg-12">
         <h1 class="page-header">Update Employee</h1>
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
                           {!! Form::select('designation', $designation, null, ['class' => 'form-control', 'required' => true, 'placeholder' => "Choose One", 'id' => 'designation']) !!}
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
                        {!! Form::label('dob', 'Date of Birth *', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::date('dob', null, ['class' => 'form-control', 'id' => 'dob', 'required' => true]) !!}
                           <p id="dob-error" class="help-block text-danger" style="display:none;"></p>
                           {!! $errors->first('dob', '<p class="help-block">:message</p>') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                        {!! Form::label('gender', 'Gender *', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::select('gender', ['male' => 'Male', 'female' => 'Female'], null, ['class' => 'form-control', 'required' => true, 'id' => 'gender']) !!}
                           {!! $errors->first('gender', '<p class="help-block">:message</p>') !!}
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
      'required' => true,
      'placeholder' => 'Select Role'
   ]) !!}
                           {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
                        </div>
                     </div>
                  </div>

               </div>
            </div>
         </div>
         <div class="panel panel-default">
            <div class="panel-heading">
               Update Password
            </div>
            <div class="panel-body">
               <div class="col-md-12 split">
                  <!-- Current Password -->

               </div>

               <div class="col-md-12 split">
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                        {!! Form::label('password', 'Password', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6 input-group">
                           {!! Form::password('password', ['class' => 'form-control', 'id' => 'password']) !!}
                           <span class="input-group-addon toggle-password" data-target="password">
                              <i class="fa fa-eye"></i>
                           </span>
                        </div>
                        <p class="help-block">Leave blank if you don't want to change the password.</p>
                        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                     </div>
                  </div>

                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                        {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6 input-group">

                           {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation']) !!}
                           <span class="input-group-addon toggle-password" data-target="password_confirmation">
                              <i class="fa fa-eye"></i>
                           </span>
                        </div>

                        {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
                        <small id="confirmMessage" class="help-block"></small>
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
                           {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => "Enter Address"]) !!}
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
                           {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => "Enter City"]) !!}
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
                           {!! Form::text('state', null, ['class' => 'form-control', 'placeholder' => "Enter State"]) !!}
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
                           {!! Form::select('expLevel', ['0' => 'Fresher', '1' => 'Experience', '2' => 'Intern'], null, ['class' => 'form-control', 'id' => 'expLevel']) !!}
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
                        {!! Form::label('bankAccountName', 'AccountHolder Name ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankAccountName', null, ['class' => 'form-control',]) !!}
                           {!! $errors->first('bankAccountName', '
                                                                        <p class="help-block">:message</p>
                                                                        ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankAccountNo') ? 'has-error' : ''}}">
                        {!! Form::label('bankAccountNo', 'Account No ', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankAccountNo', null, ['class' => 'form-control',]) !!}
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
                        {!! Form::label('bankName', 'Bank Name', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankName', null, ['class' => 'form-control']) !!}
                           {!! $errors->first('bankName', '
                                                                        <p class="help-block">:message</p>
                                                                        ') !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 split">
                     <div class="form-group {{ $errors->has('bankIfscCode') ? 'has-error' : ''}}">
                        {!! Form::label('bankIfscCode', 'IFSC Code', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankIfscCode', null, ['class' => 'form-control']) !!}
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
                        {!! Form::label('bankBranch', 'Branch Name', ['class' => 'col-md-4 control-label']) !!}
                        <div class="col-md-6">
                           {!! Form::text('bankBranch', null, ['class' => 'form-control']) !!}
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
            <a style="margin-left:650px;"
               href="{{Auth::user()->type == 'admin' ? url('/Admin/Employee') : url('/Employee')}}"
               class="btn btn-primary">Cancel</a>
         </div>
         <div class=" col-md-1">
            {!! Form::submit(isset($submitButtonText) ? $submitButtonText : 'Save', ['class' => 'btn btn-primary']) !!}
         </div>
      </div>
      {!! Form::close() !!}
   </div>
   <script>
      document.getElementById('dob').addEventListener('change', function () {
         const dobInput = this.value;
         if (!dobInput) return;

         const dob = new Date(dobInput);
         const today = new Date();

         // Calculate age
         let age = today.getFullYear() - dob.getFullYear();
         const m = today.getMonth() - dob.getMonth();
         if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
            age--;
         }

         const errorElement = document.getElementById('dob-error');

         if (age < 20) {
            alert("You must be at least 20 years old.");
            errorElement.style.display = "block";
            errorElement.textContent = "You must be at least 20 years old.";
            this.value = ""; // Clear invalid DOB
         } else {
            errorElement.style.display = "none";
            errorElement.textContent = "";
         }
      });
   </script>
   <script>
      const password = document.getElementById("password");
      const confirmPassword = document.getElementById("password_confirmation");
      const message = document.getElementById("confirmMessage");

      // check automatically while typing confirm password
      confirmPassword.addEventListener("input", function () {
         if (confirmPassword.value === "") {
            message.textContent = "";
            return;
         }

         if (password.value === confirmPassword.value) {
            message.textContent = "✅ Passwords match";
            message.style.color = "green";
         } else {
            message.textContent = "❌ Passwords do not match";
            message.style.color = "red";
         }
      });

      document.querySelectorAll('.toggle-password').forEach(function (element) {
         element.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (input.type === "password") {
               input.type = "text";
               this.querySelector('i').classList.remove('fa-eye');
               this.querySelector('i').classList.add('fa-eye-slash');
            } else {
               input.type = "password";
               this.querySelector('i').classList.remove('fa-eye-slash');
               this.querySelector('i').classList.add('fa-eye');
            }
         });
      });
   </script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

@endsection