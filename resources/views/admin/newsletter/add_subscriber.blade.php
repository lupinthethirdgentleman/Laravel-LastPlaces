@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.add_subscriber") }}</span>
		<a href="{{URL::to('admin/news-letter/subscriber-list')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }}  </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/news-letter/add-subscriber/','class' => 'mws-form']) }}
			<div class="mws-form-inline">	
				<div class="mws-form-row">
					{{  Form::label('Name', trans("messages.system_management.name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('name', '', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('name'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('Email', trans("messages.system_management.email"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('email', '', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('email'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					<a href="{{URL::to('admin/news-letter/add-subscriber')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

@stop
