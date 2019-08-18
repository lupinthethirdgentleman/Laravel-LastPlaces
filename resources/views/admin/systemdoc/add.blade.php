@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.system_management.add_doc") }} </span>
		<a href="{{URL::to('admin/system-doc-manager')}}" class="btn btn-success btn-small align">
			{{ trans("messages.system_management.back") }} </a>
	</div>
	
	{{ Form::open(['role' => 'form','url' => 'admin/system-doc-manager/add-doc','class' => 'mws-form','enctype'=> 'multipart/form-data']) }}
	<div class="mws-panel-body no-padding">

		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{  Form::label('file', trans("messages.system_management.system_document_label_upload_document"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::file('file') }}
					<!-- Toll tip div start here -->
					<a class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
						<i class="fa fa-question-circle fa-2x"> </i>
					</a>
					<!-- Toll tip div end here -->
					<div class="error-message help-inline">
						<?php echo $errors->first('file'); ?>
					</div>
				</div>
			</div>
			<div class="mws-form-row">
				{{  Form::label('title',trans("messages.system_management.title"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('title','', ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('title'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="mws-panel-body no-padding tab-content"> 
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
				<a href="{{URL::to('admin/system-doc-manager/add-doc')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop
