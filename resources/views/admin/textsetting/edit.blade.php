@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.settings.edit_text") }}</span>
		<a href="{{URL::to('admin/text-setting')}}" class="btn btn-success btn-small align">{{ trans("messages.settings.back") }}  </a>
	</div>
	
	{{ Form::open(['role' => 'form','url' =>'admin/text-setting/update-new-text/'.$result->id,'class' => 'mws-form']) }}
	
	<div class="mws-panel-body no-padding">
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{  Form::label('value', trans("messages.settings.value"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('value',$result->value,['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('value'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.settings.save') }}" class="btn btn-danger">
			
			<a href="{{URL::to('admin/text-setting/edit-new-text/'.$result->id )}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.settings.reset") }}</a>
		</div>
	</div>
	 {{ Form::close() }}
</div>

@stop
