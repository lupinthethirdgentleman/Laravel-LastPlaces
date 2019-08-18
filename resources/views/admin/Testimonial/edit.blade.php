@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_edit") }} </span>
		<a href='{{route("$modelName.index")}}' class="btn btn-success btn-small align">{{ trans("messages.global.back")}} </a>
	</div>
	<div  class="default_language_color">
		{{  Config::get('default_language.message') }}
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
	{{ Form::open(['role' => 'form','url' => 'admin/testimonial-manager/edit-testimonial/'.$model->id,'class' => 'mws-form', 'files' => true]) }}

	<div class="mws-panel-body no-padding tab-content">
		@foreach($languages as $value)
			<?php $i = $value->id; ?>
			<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  HTML::decode(Form::label('client_name', trans("messages.$modelName.client_name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])); }}
						<div class="mws-form-item">
							{{ Form::text("data[$i][client_name]",isset($multiLanguage[$i]['client_name'])?$multiLanguage[$i]['client_name']:'', ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('client_name') : ''; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  HTML::decode(Form::label('client_designation', trans("messages.$modelName.client_designation").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])); }}
						<div class="mws-form-item">
							{{ Form::text("data[$i][client_designation]",isset($multiLanguage[$i]['client_designation'])?$multiLanguage[$i]['client_designation']:'', ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('client_designation') : ''; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  HTML::decode(Form::label('title', trans("messages.$modelName.title").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::text("data[$i][title]",isset($multiLanguage[$i]['title'])?$multiLanguage[$i]['title']:'', ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('title') : ''; ?>
							</div>
						</div>
					</div>
				</div>

				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  HTML::decode( Form::label('Image', trans("Testimonial Image"), ['class' => 'mws-form-label'])) }}
							<div class="mws-form-item">
								{{ Form::file("data[$i][image]") }}
					
								<?php 
								$oldImage	=	Input::old('image');
								$image		=	isset($oldImage) ? $oldImage : $multiLanguage[$i]['image'];
								?>
								@if($image !=''  && TESTIMONIAL_IMAGE_ROOT_PATH.$image)
									{{ HTML::image( TESTIMONIAL_IMAGE_URL.$multiLanguage[$i]['image'], $multiLanguage[$i]['image'] , array(  'width' => 200, 'height'=> 200 )) }}
								@endif
								<div class="error-message help-inline">
									<?php echo ($i ==  $language_code ) ? $errors->first('image') : ''; ?>
								</div>
							</div>
					</div>
				</div>

				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  HTML::decode(Form::label($i.'_body', trans("messages.$modelName.comment").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$i][comment]",isset($multiLanguage[$i]['comment'])?$multiLanguage[$i]['comment']:'', ['class' => 'small','id' => 'comment'.$i]) }}
							<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('comment') : ''; ?>
							</span>
						</div>
						<script type="text/javascript">
							/* For CKeditor */
							CKEDITOR.replace( <?php echo 'comment'.$i; ?>,
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
				<input type="submit" value="Save" class="btn btn-danger">
				<a href='{{URL::to('admin/testimonial-manager/edit-testimonial/'.$model->id)}}'  class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop
