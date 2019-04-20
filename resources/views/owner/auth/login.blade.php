@extends('layouts.auth')

@section('title')
    <title>Owner Marketplace Limbah | Log in</title>
@endsection

@section('header')
    <a href="#"><b>Owner</b> Marketplace Limbah</a>
@endsection

@section('content')
    <p class="login-box-msg">Sign in to start your session</p>
    <form action="{{ route('owner.login') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }} has-feedback">
            <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <div class="checkbox icheck">
                <label>
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
            </div>
            <!-- /.col -->
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
            <!-- /.col -->
        </div>
    </form>
@endsection