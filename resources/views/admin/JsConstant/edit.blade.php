@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.settings.table_heading_JsConstant_module_edit") }}</span>
		<a href="{{URL::to('admin/jsconstant-setting/'.$type)}}" class="btn btn-success btn-small align">{{ trans("messages.global.back") }}  </a>
	</div>
	
	{{ Form::open(['role' => 'form','url' =>'admin/jsconstant-setting/update-new-text/'.$result->id.'/'.$type,'class' => 'mws-form']) }}
	
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
			<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
			
			<a href="{{URL::to('admin/jsconstant-setting/edit-new-text/'.$result->id.'/'.$type )}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.global.reset") }}</a>
		</div>
	</div>
	 {{ Form::close() }}
</div>

@stop
