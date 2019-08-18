@extends('admin.layouts.default')

@section('content')

<?php 
$menu_nameInput	=	Input::old('menu_list');
?>	
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.edit") }}</span>
		<a href="{{URL::to('admin/menus/'.$type)}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/menus/save-menu/'.$menuid.'/'.$type,'class' => 'mws-form', 'files' => true]) }}
			{{ Form::hidden('id', $result[0]->id) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('menu_name', trans("messages.system_management.menu_name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('menu_name',(isset($menu_nameInput)&& !empty($menu_nameInput) ? $menu_nameInput : $result[0]->menu_name), ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('menu_name'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/menus/edit-menu/'.$result[0]->id.'/'.$type)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
				</div> 
			</div>
		{{ Form::close() }}
	</div>    	
</div>
@stop
