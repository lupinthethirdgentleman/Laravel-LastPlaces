@extends('admin.layouts.default')
@section('content')

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

{{ HEADER_IMAGE_INFO }}

<div class="mws-panel grid_8">
	<!-- add new and back button-->
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_edit") }}</span>
		<a href='{{route("$modelName.edit","$model->id")}}'  class="btn btn-success btn-small align">{{trans("messages.global.back") }} </a>
	</div>
	
	
	{{ Form::open(['role' => 'form','route' =>"$modelName.update",'class' => 'mws-form', 'files' => true]) }}
		{{ Form::hidden('id', $model->id) }}
		<div class="mws-panel-body no-padding">
			<!-- common field message -->
			
			<!-- common field-->
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  HTML::decode(Form::label('image', trans("Image"), ['class' => 'mws-form-label'])) }}
					<div class="mws-form-item">
						{{ Form::file('image') }}
						@if(File::exists(HEADER_IMAGE_ROOT_PATH.$model->value))
						{{ HTML::image( HEADER_IMAGE_URL.$model->value, $model->value , array( 'width' => 70, 'height' => 70 )) }}
						@endif
						<!-- Toll tip div start here -->
						<span class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
						<i class="fa fa-question-circle fa-2x"> </i>
						</span>
						<!-- Toll tip div end here -->
						<div class="error-message help-inline">
							<?php echo $errors->first('image'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<!-- button-->
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
				<a href='{{route("$modelName.edit","$model->id")}}' class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
			</div>
		</div>
	</div>

{{ Form::close() }}
</div>
<!-- for tooltip -->
{{ HTML::script('js/bootstrap.min.js') }}
<!-- for tooltip -->
<script>
	/** For tooltip */
	$('[data-toggle="tooltip"]').tooltip();   
</script>
@stop

