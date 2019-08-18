@extends('admin.layouts.default')

@section('content')

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.add") }}  </span>
		<a href="{{URL::to('admin/menus/'.$type)}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }}  </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/menus/save-menu/'.$type,'class' => 'mws-form', 'files' => true]) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('parent_id', trans("messages.system_management.parent_page"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('parent_id',array('0'=>'Select Parent Page')+$listMenu,null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('parent_id'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('menu_name',trans("messages.system_management.menu_name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('menu_name', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('menu_name'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row">
					{{  Form::label('url',trans("messages.system_management.url"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('url', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('url'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('order', trans("messages.system_management.order"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('order', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('order'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/menus/'.$type.'/add-menu')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>
@stop
