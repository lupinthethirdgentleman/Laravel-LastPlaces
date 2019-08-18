@extends('admin.layouts.default')

@section('content')

<!-- CKeditor start here-->
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
<!-- CKeditor ends-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header" style="height: 46px;">
		<span>{{ trans("messages.system_management.add")}}  </span>
		<a href="{{URL::to('admin/meta-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }}  </a>
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
				<?php $i++; ?>
			@endforeach
		</ul>
	</div>
	
	{{ Form::open(['role' => 'form','url' => 'admin/meta-manager/add-meta','class' => 'mws-form']) }}	
	<div class="mws-panel-body no-padding tab-content">
		<div class="mws-panel-body no-padding">
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("messages.system_management.language_field") }}</b>
			</div>
			<div class="mws-form-inline" >
				<div class="mws-form-row ">
					{{  Form::label('page name',  trans("messages.system_management.page_name") , ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select(
							'page_id',
							array(''=>'Select page')+Config::get('PAGE_LIST'),
							'null',
							['class' => 'small url_text']
						) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('page_id'); ?>
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
						{{  Form::label('meta_keyword', trans("messages.system_management.meta_keyword"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::text("data[$i][meta_keyword]",'', ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('meta_keyword') : ''; ?>
							</div>
						</div>
					</div>
					<div class="mws-form-row ">
						{{  Form::label('meta_title', trans("messages.system_management.meta_title"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::text("data[$i][meta_title]",'', ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('meta_title') : ''; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  Form::label($i.'_body', trans("messages.system_management.description") , ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::textarea("data[$i][description]",'', ['class' => 'small','id' => 'description'.$i]) }}
							<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('description') : ''; ?>
							</span>
						</div>
					</div>
				</div>
			</div> 
			<?php $i++ ; ?>
		@endforeach
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
				<a href="{{URL::to('admin/meta-manager/add-meta')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
			</div>
		</div>
	</div>
	{{ Form::close() }} 
</div>

@stop
