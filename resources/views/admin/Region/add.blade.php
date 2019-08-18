@extends('admin.layouts.default')

@section('content')
<!-- CKeditor start here-->

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

<div class="mws-panel grid_8">

	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_add") }} </span>
		<a href='{{ route("$modelName.index")}}' class="btn btn-success btn-small align">
		{{ trans("messages.global.back") }} </a>
	</div>

	@if(count($languages) > 1)
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
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
			
		</div>
	</div>

	<div class="mws-panel-body no-padding tab-content"> 
				@foreach($languages as $value)
					<?php $i = $value->id; ?>
					<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
						<div class="mws-form-inline">


						<div class="mws-form-row">
							{{ HTML::decode( Form::label($i.'.name', trans("messages.$modelName.region_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						
							<div class="mws-form-item">
								{{ Form::text("data[$i][".'name'."]",'', ['class' => 'small']) }}
								<div class="error-message help-inline">
										<?php echo ($i ==  $language_code ) ? $errors->first('name') : ''; ?>								</div>
							</div>
						</div>


							<div class="mws-form-row ">
								{{ HTML::decode( Form::label($i.'.heading', trans("messages.$modelName.region_heading").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
								
								<div class="mws-form-item">
									{{ Form::text("data[$i][".'heading'."]",'', ['class' => 'small']) }}
									<div class="error-message help-inline">
										<?php echo ($i ==  $language_code ) ? $errors->first('heading') : ''; ?>
									</div>
								</div>
							</div>

							<div class="mws-form-row ">
						{{ HTML::decode( Form::label($i.'_body', trans("messages.$modelName.region_introduction").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						
						<div class="mws-form-item">
							{{ Form::textarea("data[$i][".'introduction'."]",'', ['class' => 'small','id' => 'body_'.$i]) }}
							<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('body') : ''; ?>
							</span>
						</div>
						<script type="text/javascript">
						/* For CKEDITOR */
							
							CKEDITOR.replace( <?php echo 'body_'.$i; ?>,
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
