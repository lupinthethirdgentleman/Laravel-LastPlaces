@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.languages.add_language") }}</span>
		<a href="{{URL::to('admin/language')}}" class="btn btn-success btn-small align">{{ trans("messages.languages.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' =>'admin/language/save-language','class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('Title', trans("messages.languages.title"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('title', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('title'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{  Form::label('Order', trans("messages.languages.language_code"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('languagecode', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('languagecode'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row" id="link_div">
					{{  Form::label('Order', trans("messages.languages.folder_code"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('foldercode', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('foldercode'); ?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.languages.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/language/add-language')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.languages.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

@stop
