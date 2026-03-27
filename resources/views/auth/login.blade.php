@extends('layouts.user')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Login</div>

                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('user.login') }}">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email"
                                        value="{{ old('email') }}" required autofocus>

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label for="password" class="col-md-4 control-label">Password</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="password" type="password" class="form-control" name="password" required>
                                        <span class="input-group-addon" style="cursor: pointer;" onclick="togglePassword()">
                                            <i id="togglePasswordIcon" class="fa fa-eye"></i>
                                        </span>
                                    </div>

                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <script>
                                function togglePassword() {
                                    const passwordField = document.getElementById("password");
                                    const icon = document.getElementById("togglePasswordIcon");
                                    if (passwordField.type === "password") {
                                        passwordField.type = "text";
                                        icon.classList.remove("fa-eye");
                                        icon.classList.add("fa-eye-slash");
                                    } else {
                                        passwordField.type = "password";
                                        icon.classList.remove("fa-eye-slash");
                                        icon.classList.add("fa-eye");
                                    }
                                }
                            </script>

                            <input type="hidden" name="module" value="1">

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            Remember Me
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <!-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                            Forgot Your Password?
                                        </a> -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection