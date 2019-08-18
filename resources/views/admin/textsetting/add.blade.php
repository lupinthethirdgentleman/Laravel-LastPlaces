@extends('admin.layouts.default')

@section('content')

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}


<div class="mws-panel grid_8">
	<div class="mws-panel-header"  style="height: 46px;">
		<span> {{ trans("messages.settings.add_new_text") }}</span>
		<a href="{{URL::to('admin/text-setting')}}" class="btn btn-success btn-small align">{{ trans("messages.settings.back") }} </a>
	</div>
	@if(count($languages)>1)
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">
				<?php $i = 1 ;  ?>
				@foreach($languages as $value)
				
					<li class="{{ ($value->id ==  $language_code )? 'active':'' }}">
						<a data-toggle="tab" href="#{{ $i }}div">
							{{ $value -> title }}
						</a>
					</li>
					<?php $i++; ?>
				@endforeach
			</ul>
		</div>
	@endif
	{{ Form::open(['role' => 'form','url' =>'admin/text-setting/save-new-text','class' => 'mws-form']) }}
	
	<div class="mws-panel-body no-padding">
		@if(count($languages)>1)
			<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
				<b>{{ trans("messages.settings.language_field") }}</b>
			</div>
		@endif
		<div class="mws-form-inline">
			<div class="mws-form-row">
				{{  Form::label('Key',trans("messages.settings.key"), ['class' => 'mws-form-label']) }}
				<div class="mws-form-item">
					{{ Form::text('key', null, ['class' => 'small']) }}
					<div class="error-message help-inline">
						<?php echo $errors->first('key'); ?>
					</div>
				</div>
			</div>
			
		</div>
	</div>  
	
	
	<div class="mws-panel-body no-padding tab-content"> 
		<?php $j = 1 ; ?>
		@foreach($languages as $value)
			<div id="{{ $j }}div" class="tab-pane {{ ($j ==  $language_code )?'active':'' }} ">
				<div class="mws-form-inline">
					<div class="mws-form-row ">
						{{  Form::label('value', trans("messages.settings.value"), ['class' => 'mws-form-label']) }}
						<div class="mws-form-item">
							{{ Form::text("data[$value->id]",null, ['class' => 'small']) }}
							<div class="error-message help-inline">
								<?php echo ($j ==  $language_code ) ? $errors->first('value') : ''; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php $j++; ?>
		@endforeach
	</div>

	
	<div class="mws-button-row">
		<div class="input" >
			<input type="submit" value="{{ trans('messages.settings.save') }}" class="btn btn-danger">
			
			<a href="{{URL::to('admin/text-setting/add-new-text')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.settings.reset") }}</a>
		</div>
	</div>
	
	{{ Form::close() }}
	
</div>
@stop
