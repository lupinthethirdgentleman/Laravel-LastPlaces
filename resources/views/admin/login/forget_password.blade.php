@extends('admin.layouts.login_layout')

@section('content')

<div id="mws-login">
	<h1>Forgot Password</h1>
	<div class="mws-login-lock"><i class="icon-lock"></i></div>
	<div id="mws-login-form">
		{{ Form::open(['role' => 'form','url' => 'admin/send_password']) }}
		<div class="mws-form-row">
			<div class="mws-form-item">
				{{ Form::text('email', null, ['placeholder' => 'Email', 'style'=>'width:100%','class'=>'mws-login-username required']) }}
			</div>
			<div class="error-message help-inline">
				<?php echo $errors->first('email'); ?>
			</div>
		</div>
		<div align="right" class="mws-form-row">
			<input type="submit" value="Submit" class="btn btn-primary mws-login-button" style="width:35% !important;">
			<a style="width:65px !important;" class="btn btn-danger mws-info-button" href="{{ URL::to('/admin')}}">Cancel</a>
		</div>
		{{ Form::close() }}
	</div>
</div>


@stop

