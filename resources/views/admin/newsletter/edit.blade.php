@extends('admin.layouts.default')

@section('content')

<!-- chosen select box css and js start here-->
{{ HTML::style('css/chosen.css') }}
{{ HTML::script('js/chosen.jquery.js') }}
<!-- chosen select box css and js end here-->

<!-- ckeditor js start here-->
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- ckeditor js end here-->

<!-- datetime picker js and css start here-->
{{ HTML::script('js/admin/jui/js/jquery-ui-1.9.2.min.js') }}
{{ HTML::script('js/admin/jui/js/timepicker/jquery-ui-timepicker.min.js') }}
{{ HTML::script('js/admin/prettyCheckable.js') }}
{{ HTML::style('css/admin/jui/css/jquery.ui.all.css') }}
{{ HTML::style('css/admin/prettyCheckable.css') }}
{{ HTML::style('css/admin/timepicker.css') }}
<!-- date time picker js and css and here-->

<script type="text/javascript">

/* For datetimepicker */
	$(function(){
		 
		$(".chzn-select").chosen();
		$('#scheduled_time').datetimepicker({ 
			timeFormat: "hh:mm:ss tt",
			dateFormat: 'yy-mm-dd',
			ampm: true,
			minDate: new Date(<?php echo date('Y,m-1,d,H,i');  ?>),
			//changeMonth: true,
			//changeYear : true,
		});	
		
	});
	
</script>	
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.edit_newsletter") }}</span>
		<a href="{{URL::to('admin/news-letter')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/news-letter/edit-template/'.$result->id,'class' => 'mws-form']) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('scheduled_time', trans("messages.system_management.scheduled_time"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('scheduled_time', $result->scheduled_time, ['class' => 'small','readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('scheduled_time'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('subject', trans("messages.system_management.subject"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('subject', $result->subject, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				
				<div class="mws-form-row">
					{{  Form::label('newsletter_subscriber_id',trans("messages.system_management.select_subscriber"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('newsletter_subscriber_id[]',$subscriberArray ,$allReadySubscriberArray, ['class' => 'chzn-select' , 'style' => 'width:55%','data-placeholder'=>'Select Subscribers','multiple'=>'multiple']) }}
					
						<div class="error-message help-inline">
							<?php echo $errors->first('newsletter_subscriber_id'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('constant', trans("messages.system_management.constants"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						<?php $constantArray = Config::get('newsletter_template_constant'); ?>
						{{ Form::select('constant', $constantArray,'', ['id' => 'constants','empty' => 'Select one','class' => 'small']) }}
						
						<span style = "padding-left:20px;padding-top:0px; valign:top">
							<a onclick = "return InsertHTML()" href="javascript:void(0)" class="btn  btn-success no-ajax"><i class="icon-white "></i>{{ trans("messages.system_management.insert_variable") }} </a>
						</span>
						<div class="error-message help-inline">
							<?php echo $errors->first('constants'); ?>
						</div>
						
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('body', trans("messages.system_management.email_body"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::textarea("body",$result->body, ['class' => 'small','id' => 'body']) }}
						<span class="error-message help-inline">
							<?php echo $errors->first('body'); ?>
						</span>
					</div>
					<script type="text/javascript">
						// <![CDATA[
						CKEDITOR.replace( 'body',
						{
							height: 350,
							width: 600,
							filebrowserUploadUrl : '<?php echo URL::to('base/uploder'); ?>',
							filebrowserImageWindowWidth : '640',
							filebrowserImageWindowHeight : '480',
							enterMode : CKEDITOR.ENTER_BR
						});
						//]]>		
					</script>
				</div>
			</div>
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value="{{ trans('messages.system_management.save') }}" class="btn btn-danger">
					
					<a href="{{URL::to('admin/news-letter/edit-template/'.$result->id)}}" class="btn primary"><i class=\"icon-refresh\"></i>{{ trans('messages.system_management.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>
<script type='text/javascript'>
	/* this function insert defined onstant on button click */
	function InsertHTML() {
		var strUser = document.getElementById("constants").value;
		if(strUser != ''){
			var newStr = '{'+strUser+'}';
			var oEditor = CKEDITOR.instances["body"] ;
			oEditor.insertHtml(newStr) ;	
		}
    }
</script>

@stop
