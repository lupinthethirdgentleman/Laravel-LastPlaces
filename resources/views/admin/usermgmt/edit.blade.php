@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/jquery-ui.css') }}
{{HTML::script('js/jquery-ui.min.js') }} 


<style>
.mws-form-item.userPassword {
    margin-bottom: 2% !important;
    width: 90% !important;
}
.mws-form-item.userRadio input {
    width: auto !important;
}
</style>

<!--set width of  Select box on date picker -->

<div class="mws-panel grid_8">
		<div id="overlay" style="display:none;">
		<img src="<?php echo WEBSITE_IMG_URL;  ?>admin/ajax-loader.GIF" class="loading_circle" alt="Loading..." />
	</div>
			
	<div class="mws-panel-header">
		<span> {{ trans("messages.user_management.edit_user") }}</span>
		<a href="{{URL::to('admin/users')}}" class="btn btn-success btn-small align">{{ trans("messages.user_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/users/edit-user/'.$userDetails->id,'class' => 'mws-form','files'=>'true']) }}
			
				<div class="mws-form-row">
				<div class="mws-form-cols">
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('first_name',trans("messages.user_management.first_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('first_name',$userDetails->first_name) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('first_name'); ?>
							</div>
						</div>
					</div>
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('last_name',trans("messages.user_management.last_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('last_name',$userDetails->last_name) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('last_name'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="mws-form-cols">
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('email',$userDetails->email) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('email'); ?>
							</div>
						</div>
					</div>
				
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('phone', trans("messages.user_management.phone").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('phone',$userDetails->phone) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('phone'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/users/edit-user/'.$userDetails->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_management.reset") }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

<!-- for tooltip -->
{{ HTML::script('js/bootstrap.min.js') }}
<!-- for tooltip -->

<script>
	/** For tooltip */
	$('[data-toggle="tooltip"]').tooltip();   
</script>


@stop
