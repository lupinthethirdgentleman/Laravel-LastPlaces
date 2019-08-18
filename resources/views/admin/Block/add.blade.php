@extends('admin.layouts.default')
@section('content')

<!-- CKeditor start here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<!-- heading and  back button-->
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_add") }} </span>
		<a href='{{ route("$modelName.index")}}' class="btn btn-success btn-small align">
		{{ trans("messages.global.back") }} </a>
	</div>
	
	<!-- multilanguage tab button -->
	@if(count($languages) > 1)
		<!--mandatory message -->
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
		
		<!--multilanguage tab -->
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">
				@foreach($languages as $value)
				<?php $i = $value->id; ?>
				<li class=" {{ ($i ==  $language_code )?'active':'' }}">
					<a data-toggle="tab" href="#{{ $i }}div">
					{{ $value -> title }}
					</a>
				</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	{{ Form::open(['role' => 'form','route' => "$modelName.save",'class' => 'mws-form']) }}
		<div class="mws-panel-body no-padding">
			@if(count($languages) > 1)
				<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("messages.global.language_field") }}</b>
				</div>
			@endif
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('page_name',trans("messages.$modelName.page_name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('page_name','', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('page_name'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('block_name', trans("messages.$modelName.block_name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text("block_name",'', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('block_name'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="mws-panel-body no-padding tab-content">
			@foreach($languages as $value)
			<?php $i = $value->id; ?>
				<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
					<div class="mws-form-inline">
						<div class="mws-form-row ">
							{{  Form::label($i.'_body', trans("messages.$modelName.description"), ['class' => 'mws-form-label']) }}
							<div class="mws-form-item">
								{{ Form::textarea("data[$i][description]",'', ['class' => 'small','id' => 'description'.$i]) }}
								<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('description') : ''; ?>
								</span>
							</div>
							<script type="text/javascript">
								/* CKEDITOR fro description */
								CKEDITOR.replace( <?php echo 'description'.$i; ?>,
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
			<!-- button-->
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
					<a href="{{ route($modelName.'.add')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
				</div>
			</div>
		</div>
	{{ Form::close() }} 
</div>
@stop

