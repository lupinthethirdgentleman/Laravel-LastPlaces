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
	$(function(){
		$(".chzn-select").chosen();
		$('#scheduled_time').datetimepicker({ 
			timeFormat: "hh:mm:ss tt",
			dateFormat: 'yy-mm-dd',
			ampm: true,
			minDate: new Date(<?php echo date('Y,m-1,d,H,i');  ?>)
		});	
	});
</script>	
<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.send_newsletter") }}</span>
		<a href="{{URL::to('admin/news-letter/newsletter-templates')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/news-letter/send-newsletter-templates/'.$result->id,'class' => 'mws-form']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('scheduled_time', 'Scheduled Date', ['class' => 'mws-form-label','readonly']) }}
					<div class="mws-form-item">
						{{ Form::text('scheduled_time', '', ['class' => 'small','readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('scheduled_time'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('subject', 'Subject', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('subject', $result->subject, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('newsletter_subscriber_id', 'Subscribers', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('newsletter_subscriber_id[]' ,$subscriberArray , null , ['class' => 'chzn-select' , 'style' => 'width:55%','data-placeholder'=>'Select Subscribers','multiple'=>'multiple']) }}
						(Leave blank for select all)
						<div class="error-message help-inline">
							<?php echo $errors->first('newsletter_subscriber_id'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('constant', 'Constants', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						<?php $constantArray = Config::get('newsletter_template_constant'); ?>
						{{ Form::select('constant', $constantArray,'', ['id' => 'constants','empty' => 'Select one','class' => 'small']) }}
						
						<span style = "padding-left:20px;padding-top:0px; valign:top">
							<a onclick = "return InsertHTML()" href="javascript:void(0)" class="btn  btn-success no-ajax"><i class="icon-white "></i>{{ trans("messages.system_management.insert_variable") }}</a>
						</span>
						<div class="error-message help-inline">
							<?php echo $errors->first('constants'); ?>
						</div>
						
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('body', 'Email Body', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::textarea("body",$result->body, ['class' => 'small','id' => 'body']) }}
						<span class="error-message help-inline">
							<?php echo $errors->first('body'); ?>
						</span>
					</div>
					<script type="text/javascript">
					/* For CKeditor */
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
					
					<a href="{{URL::to('admin/myaccount')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans("messages.system_management.reset") }}</a>
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
