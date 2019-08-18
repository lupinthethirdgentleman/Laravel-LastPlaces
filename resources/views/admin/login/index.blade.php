@extends('admin.layouts.login_layout')

@section('content')

<div id="mws-login">
	<h1>Login</h1>
	<div class="mws-login-lock"><i class="icon-lock"></i></div>
	<div id="mws-login-form">
		{{ Form::open(['role' => 'form','url' => 'admin/login']) }}
		<div class="mws-form-row">
			<div class="mws-form-item">
				{{ Form::text('email', null, ['placeholder' => 'Email', 'class' => 'mws-login-username required']) }}
			</div>
		</div>
		<div class="mws-form-row">
			<div class="mws-form-item">
				{{ Form::password('password', ['placeholder' => 'Password', 'class' => 'mws-login-password required']) }}
			</div>
		</div>
		<div class="mws-form-row">
			<input type="submit" value="Login" class="btn btn-primary mws-login-button">
		</div>
		<div class="mws-form-row mws-inset" id="mws-login-remember">
			<a href="{{ URL::to('admin/forget_password')}}" style="color:#fff">Forgot your password?</a>					
		</div>
		{{ Form::close() }}
	</div>
</div>

@stop

