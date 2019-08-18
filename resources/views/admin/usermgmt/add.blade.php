
@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/jquery-ui.css') }}  
{{ HTML::script('js/jquery-ui.min.js') }} 

<script type="text/javascript">

$(document).ready(function() {

	 
</script>
<!--set width of  Select box on date picker -->


<div class="mws-panel grid_8">	
	<div id="overlay" style="display:none;">
		<img src="<?php echo WEBSITE_IMG_URL;  ?>admin/ajax-loader.GIF" class="loading_circle" alt="Loading..." />
	</div>
				
	<div class="mws-panel-header">
		<span> {{ trans("messages.user_management.add_user") }} </span>
		<a href="{{URL::to('admin/users')}}" class="btn btn-success btn-small align">{{ trans("messages.user_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/users/add-user','class' => 'mws-form','files'=>'true']) }}
			<div class="mws-form-row">
				<div class="mws-form-cols">
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('First Name',trans("messages.user_management.first_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('first_name','') }}
							<div class="error-message help-inline">
								<?php echo $errors->first('first_name'); ?>
							</div>
						</div>
					</div>
					<div class="mws-form-col-4-8">
                    {{ HTML::decode( Form::label('Last Name',trans("messages.user_management.last_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
                        <div class="mws-form-item">
                            {{ Form::text('last_name','') }}
                            <div class="error-message help-inline">
                                <?php echo $errors->first('last_name'); ?>	
                            </div>
                        </div>
                    </div> 

					<?php /*<div class="mws-form-col-4-8">
						{{ HTML::decode( Form::label('gender', trans("messages.user_management.gender").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item clearfix userRadio">
								
						
							{{ Form::radio('gender','male',false,array('id'=>'male_id')) }}
							{{ Form::label('male_id',trans("messages.user_management.male")) }}
						
							{{ Form::radio('gender','female',false,array('id'=>'female_id')) }}
							{{ Form::label('female_id',trans("messages.user_management.female")) }}
							
							<div class="error-message help-inline">
								<?php echo $errors->first('gender'); ?>
							</div>
					</div>
				</div> */ ?>
			</div>
			<div class="mws-form-cols">
					<div class="mws-form-col-4-8">
					{{ HTML::decode( Form::label('email', trans("messages.user_management.email").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('email','') }}
							<div class="error-message help-inline">
								<?php echo $errors->first('email'); ?>
							</div>
						</div>
					</div>

					<div class="mws-form-col-4-8">
						{{ HTML::decode( Form::label('phone_number', trans("messages.user_management.phone").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('phone_number','') }}
							<div class="error-message help-inline">
								<?php echo $errors->first('phone_number'); ?>
							</div>
						</div>
					</div> 


			</div>
		</div>
			
			<div class="mws-form-row">
				
			<?php /*<div class="mws-form-row">
				
				

					
				</div>*/?>
			</div>
		
			<?php /*<div class="mws-form-row">
				<div class="mws-form-cols">
					<div class="mws-form-col-4-8">
						{{ HTML::decode( Form::label('faulty', trans("messages.user_management.faulty").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text('faulty','') }}
							<div class="error-message help-inline">
								<?php echo $errors->first('faulty'); ?>
							</div>
						</div>
					</div>
					<div class="mws-form-col-4-8">
						{{  Form::label('civil_id', trans("messages.user_management.civil_id"), ['class' => 'mws-form-label']) }}	
						<div class="mws-form-item">
							{{ Form::text('civil_id','') }}
						</div>
					</div> *?>



						<?php /* ?>

					{{ HTML::decode( Form::label('image', trans("messages.user_management.profile_image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label floatleft'])) }}
						<!-- Toll tip div start here -->
						<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
							<i class="fa fa-question-circle fa-2x"> </i>
						</span>
						<!-- Toll tip div end here -->
						<div class="mws-form-item">
							{{ Form::file('image') }}
						
							<div class="error-message help-inline">
								<?php echo $errors->first('image'); ?>
							</div>
						</div>
		


					</div>
				</div>*/ ?>
		
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.user_management.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/users/add-user')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.user_management.reset") }}</a>
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
