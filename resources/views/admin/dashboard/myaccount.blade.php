@extends('admin.layouts.default')

@section('content')

<?php
$userInfo	=	Auth::user();
$username	=	(isset($userInfo->username)) ? $userInfo->username : '';
$email		=	(isset($userInfo->email)) ? $userInfo->email : '';
?>
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'My Account' }}</span>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/myaccount','class' => 'mws-form','files'=>'true']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
					
					{{ HTML::decode( Form::label('username', trans("messages.user_management.user_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('username', $username, ['class' => 'small']) }}  
						<div class="error-message help-inline">
							<?php echo $errors->first('username'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::text('email', $email, ['class' => 'small','readonly' => 'readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('email'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
						{{ HTML::decode( Form::label('image', trans("messages.user_management.profile_image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						
						<!-- Toll tip div start here -->
							
							<div class="mws-form-item">
							{{ Form::file('image') }}
								
							<?php 
							$oldImage	=	Input::old('image');
							$image		=	isset($oldImage) ? $oldImage : $userInfo->image;
							?>
							@if($image !=''  && USER_PROFILE_IMAGE_ROOT_PATH.$image)
								{{ HTML::image( USER_PROFILE_IMAGE_URL.$userInfo->image, $userInfo->image , array( 'width' => 70, 'height' => 70 )) }}
							@endif
							<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
								<i class="fa fa-question-circle fa-2x"> </i>
							</span>
							<!-- Toll tip div end here -->
								<div class="error-message help-inline">
									<?php echo $errors->first('image'); ?>
								</div>
							</div>
					
			</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('email', trans("messages.dashboard.old_password"), ['class' => 'mws-form-label'])) }}
					
					<div class="mws-form-item">
						{{ Form::password('old_password',['class' => 'small']) }}
						<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo trans("messages.user_management.password_help_message"); ?>" style="cursor:pointer;">
							<i class="fa fa-question-circle fa-2x"> </i>
						</span>
						<!-- Toll tip div end here -->
						<div class="error-message help-inline">
							<?php echo $errors->first('old_password'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{ HTML::decode( Form::label('email', trans("messages.dashboard.new_password"), ['class' => 'mws-form-label'])) }}
					
					<div class="mws-form-item">
						{{ Form::password('new_password', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('new_password'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('email', trans("messages.dashboard.confirm_password"), ['class' => 'mws-form-label'])) }}
					
					<div class="mws-form-item">
						{{ Form::password('confirm_password', ['class' => 'small']) }}
						
						<div class="error-message help-inline">
							<?php echo $errors->first('confirm_password'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="Save" class="btn btn-danger">
					
					<a href="{{URL::to('admin/myaccount')}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
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
