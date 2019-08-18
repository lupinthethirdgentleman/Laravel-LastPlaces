@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

{{ IMAGE_INFO }}
	
<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_add") }} </span>
		<a href='{{ route("$modelName.index")}}' class="btn btn-success btn-small align">{{ trans("messages.global.back") }} </a>
	</div>
	@if(count($languages) > 1)
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">

				@foreach($languages as $value)
					<li class=" {{ ($value->id ==  $language_code )?'active':'' }}">
						<a data-toggle="tab" href="#{{ $value->id }}div">
							{{ $value -> title }}
						</a>
					</li>
					
				@endforeach
			</ul>
		</div>
	@endif
	{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form', 'files' => true]) }}
	<div class="mws-panel-body no-padding">
		@if(count($languages) > 1)
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("messages.global.language_field") }}</b>
			</div>
		@endif
	
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('image', trans("messages.$modelName.image"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::file('image') }}
						<!-- Toll tip div start here -->
						<a class='tooltipHelp' title="" data-html="true" data-toggle="tooltip"  data-original-title="<?php echo "The attachment must be a file of type:".IMAGE_EXTENSION; ?>" style="cursor:pointer;">
							<i class="fa fa-question-circle fa-2x"> </i>
						</a>
						<!-- Toll tip div end here -->
						<div class="error-message help-inline">
							<?php echo $errors->first('image'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('order', trans("messages.$modelName.order"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('order' , null , ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('order'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>   
	
	
	<div class="mws-panel-body no-padding tab-content"> 
		
		@foreach($languages as $value)
			<div id="{{ $value->id }}div" class="tab-pane {{ ($value->id ==  $language_code )?'active':'' }} ">
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  Form::label($value->id.'_body', trans("messages.$modelName.description"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$value->id][body]",'', ['class' => 'small','id' => 'body_'.$value->id]) }}
							<span class="error-message help-inline">
								<?php echo ($value->id ==  $language_code ) ? $errors->first('body') : ''; ?>
							</span>
						</div>
						<script type="text/javascript">
						/* For CKEDITOR */
							
							CKEDITOR.replace( <?php echo 'body_'.$value->id; ?>,
							{
								height: 350,
								width: 600,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
								filebrowserImageWindowWidth : '640',
								filebrowserImageWindowHeight : '480',
								enterMode : CKEDITOR.ENTER_BR
							});
								
						</script>
					</div>
				</div>
			</div> 
			
		@endforeach
	</div>			
				
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
			<a href="{{ route($modelName.'.add')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
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
