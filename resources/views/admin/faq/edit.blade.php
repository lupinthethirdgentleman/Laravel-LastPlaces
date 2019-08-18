@extends('admin.layouts.default')

@section('content')

<!--- ckeditor js and css start here  here -->
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- CKeditor js  end css end here-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.system_management.edit_question") }} </span>
		<a href="{{URL::to('admin/faqs-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div  class="default_language_color">
		{{ Config::get('default_language.message') }}
	</div>
	<div class="wizard-nav wizard-nav-horizontal">
		<ul class="nav nav-tabs">
			<?php $i = 1 ; ?>
			@foreach($languages as $value)
				<li class=" {{ ($i ==  $language_code )?'active':'' }}">
					<a data-toggle="tab" href="#{{ $i }}div">
						{{ $value -> title }}
					</a>
				</li>
				<?php $i++;  ?>
			@endforeach
		</ul>
	</div>
	{{ Form::open(['role' => 'form','url' => 'admin/faqs-manager/edit-faqs/'.$AdminFaq->id,'class' => 'mws-form']) }}
	
	<div class="mws-panel-body no-padding tab-content"> 
		<div class="mws-panel-body no-padding">
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
				<b>{{ trans("messages.system_management.language_field") }}</b>
			</div>
			<div class="mws-form-inline" >
				<div class="mws-form-row ">
						{{  Form::label('topic', 'Topic', ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::select(
								'category_id',
								array(''=>'Select topic')+$listDownloadCategory,
								$AdminFaq->category_id,
								['class' => 'small url_text']
							) }}
							<div class="error-message help-inline">
								<?php echo $errors->first('category_id'); ?>
							</div>
						</div>
				</div>
			</div>
		</div>
		<?php $i = 1 ; ?>
		@foreach($languages as $value)
			<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  Form::label($i.'.question', trans("messages.system_management.question"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$i]['question']",isset($multiLanguage[$i]['question'])?$multiLanguage[$i]['question']:'', ['class' => 'small','id' => 'question_'.$i]) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('question') : ''; ?>
							</div>
						</div>
					</div>
					<div class="mws-form-row ">
						{{  Form::label($i.'_answer', trans("messages.system_management.answer"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$i]['answer']",isset($multiLanguage[$i]['answer'])?$multiLanguage[$i]['answer']:'', ['class' => 'small','id' => 'answer_'.$i]) }}
							<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('answer') : ''; ?>
							</span>
						</div>
						<script type="text/javascript">
						/* CKEDITOR for question */
							
							CKEDITOR.replace( <?php echo 'question_'.$i; ?>,
							{
								height: 200,
								width: 600,
								filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
								filebrowserImageWindowWidth : '640',
								filebrowserImageWindowHeight : '480',
								enterMode : CKEDITOR.ENTER_BR
							});
							
							/* CKEDITOR for answer */
						
							CKEDITOR.replace( <?php echo 'answer_'.$i; ?>,
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
			<?php $i++ ; ?>
		@endforeach
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
				<a href="{{URL::to('admin/faqs-manager/edit-faqs/'.$AdminFaq->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>
@stop
