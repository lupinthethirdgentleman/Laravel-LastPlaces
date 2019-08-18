@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'Add New Setting' }}</span>
		<a href="{{URL::to('admin/settings')}}" class="btn btn-success btn-small align">{{ 'Back To Setting' }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/settings/add-setting','class' => 'mws-form']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('title', 'Title', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('title', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('title'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('key', 'Key', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('key', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('key'); ?>
						</div><small>e.g., 'Site.title'</small>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('value', 'Value', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::textarea('value', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('value'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('input_type', 'Input Type', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('input_type', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('input_type'); ?>
						</div><small><em><?php echo "e.g., 'text' or 'textarea'";?></em></small>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('editable', 'Editable', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						<div class="input-prepend">
							<span class="add-on"> 
								{{ Form::checkbox('editable', null, ['class' => 'small']) }}
							</span>
							<input type="text" size="16" name="prependedInput2" id="prependedInput2" value="<?php echo "Editable"; ?>" disabled="disabled" style="width:415px;" class="small">
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="Save" class="btn btn-danger">
					
					<a href="{{URL::to('admin/settings/add-setting')}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>
@stop
