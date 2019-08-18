@extends('admin.layouts.login_layout')

@section('content')

<div id="mws-login">
	<h1>Reset Password</h1>
	<div class="mws-login-lock"><i class="icon-lock"></i></div>
	<div id="mws-login-form">
		{{ Form::open(['role' => 'form','url' => 'admin/save_password']) }}
		{{ Form::hidden('validate_string',$validate_string, []) }}
		<div class="mws-form-row">
			<div class="mws-form-item">
				{{ Form::password('new_password',  ['placeholder' => 'New Password', 'class' => 'mws-login- required']) }}
			</div>
			<div class="error-message help-inline">
				<?php echo $errors->first('new_password'); ?>
			</div>
		</div>
		<div class="mws-form-row">
			<div class="mws-form-item">
				{{ Form::password('new_password_confirmation', ['placeholder' => 'Confirm Password', 'class' => 'mws-login- required']) }}
			</div>
			<div class="error-message help-inline">
				<?php echo $errors->first('new_password_confirmation'); ?>	
			</div>
		</div>
	</div>
	<div class="mws-form-row">
		<input type="submit" value="Submit" class="btn btn-success mws-login-button">
	</div>
	{{ Form::close() }}
</div>

@stop

