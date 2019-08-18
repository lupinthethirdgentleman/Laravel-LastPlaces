@extends('admin.layouts.default')
@section('content')

{{ HTML::style('css/admin/custom_li_bootstrap.css') }}
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}

{{ IMAGE_INFO }}
<?php 
//echo "<pre>";
$authorListOptions = array();
foreach($authorList as $list){
	$key = $list->id;
	$value = $list->first_name.' '.$list->last_name;
	$authorListOptions[$key] = $value;
}

//print_r($authorListOptions);
//die;
?>
<div class="mws-panel grid_8">
	<!-- add new and back button-->
	<div class="mws-panel-header" style="height: 46px;">
		<span> {{ trans("messages.$modelName.table_heading_add") }} </span>
		<a href='{{route("$modelName.index")}}' class="btn btn-success btn-small align">{{ trans("messages.global.back") }} </a>
	</div>
	
	<!-- multilanguage tab button -->
	@if(count($languages) > 1)
		<div  class="default_language_color">
			{{ Config::get('default_language.message') }}
		</div>
		<div class="wizard-nav wizard-nav-horizontal">
			<ul class="nav nav-tabs">
				
				@foreach($languages as $value)
					<?php $i = $value->id ; ?>
					<li class=" {{ ($i ==  $language_code )?'active':'' }}">
						<a data-toggle="tab" href="#{{ $i }}div">
						{{ $value -> title }}
						</a>
					</li>
				<?php $i++; ?>
				@endforeach
			</ul>
		</div>
	@endif
	
	{{ Form::open(['role' => 'form','route' =>"$modelName.add",'class' => 'mws-form', 'files' => true]) }}
		<div class="mws-panel-body no-padding">
			<!-- common field message -->
			@if(count($languages) > 1)
				<div class="text-right mws-form-item" style="margin-right:20px; padding-top:10px; font-size: 12px;">
					<b>{{ trans("messages.global.language_field") }}</b>
				</div>
			@endif
			
			<!-- common field-->
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  HTML::decode(Form::label('image', trans("messages.$modelName.image").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
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
				<div class="mws-form-row ">
					{{ HTML::decode( Form::label('author_id', trans("Author Name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label chosen-select' ])) }}
					<div class="mws-form-item">
							{{ Form::select(
								 'author_id',
								 [null => 'Please Select Author Name']+ $authorListOptions,
								 '',
								 ['class'=>'small','id'=>'author_id']
								) 
							}}						
							<div class="error-message help-inline">
							<?php echo $errors->first('author_id'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- multii language field-->
		<div class="mws-panel-body no-padding tab-content">
			
			@foreach($languages as $value)
					<?php $i = $value->id ; ?>
				<div id="{{ $i }}div" class="tab-pane {{ ($i ==  $language_code )?'active':'' }} ">
					<div class="mws-form-inline">
						<div class="mws-form-row">
							{{  HTML::decode(Form::label('name', trans("messages.$modelName.name").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
							<div class="mws-form-item">
								{{ Form::text("data[$i]['name']" , null , ['class' => 'small']) }}
								<div class="error-message help-inline">
									<?php echo $errors->first('name'); ?>
								</div>
							</div>
						</div>
						<div class="mws-form-row ">
							{{  HTML::decode(Form::label($i.'_body', trans("messages.$modelName.description").'<span class="requireRed"> * </span>', ['class' => 'mws-form-label'])) }}
							<div class="mws-form-item">
								{{ Form::textarea("data[$i]['description']",'', ['class' => 'small','id' => 'body_'.$i]) }}
								<span class="error-message help-inline">
								<?php echo ($i ==  $language_code ) ? $errors->first('description') : ''; ?>
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
			<?php $i++ ; ?>
			@endforeach
		</div>
		
		<!-- button-->
		<div class="mws-button-row">
			<div class="input" >
				<input type="submit" value="{{ trans('messages.global.save') }}" class="btn btn-danger">
				<a href='{{route("$modelName.add")}}' class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.global.reset') }}</a>
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

