@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header"  style="height: 46px;">
		<span> {{ trans("messages.system_management.table_heading_Country_module_add") }}</span>
		<a href="{{URL::to('admin/location')}}" class="btn btn-success btn-small align">{{ trans("messages.global.back") }} </a>
	</div>
	{{ Form::open(['role' => 'form','url' =>'admin/Location-setting/save-new-text','class' => 'mws-form']) }}
	
	<div class="mws-panel-body no-padding">
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{  Form::label('country_name',trans("messages.system_management.country_name"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('country_name',null, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('country_name'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('country_iso_code',trans("messages.system_management.country_iso_code"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('country_iso_code',null, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('country_iso_code'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('country_code',trans("messages.system_management.country_code"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('country_code',null, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('country_code'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('country_order',trans("messages.system_management.country_order"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('country_order',null, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('country_order'); ?>
					</div>
				</div>
			</div>
			
		</div>
	</div>  

	
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
			
			<a href="{{URL::to('admin/location')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.global.reset") }}</a>
		</div>
	</div>
	
	{{ Form::close() }}
	
</div>
@stop
