@extends('admin.layouts.default')

@section('content')
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'Edit Word' }}</span>
		<a href="{{URL::to('admin/language-settings')}}" class="btn btn-success btn-small align">{{ 'Back To Listing' }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/language-settings/edit-setting/'.$Id,'class' => 'mws-form']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('word', 'Word', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('word', $result->msgstr, ['class' => 'small']) }}  <span class="asterisk">*</span>
						<div class="error-message help-inline">
							<?php echo $errors->first('word'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.management.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/language-settings/edit-setting/'.$Id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.management.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>
@stop
