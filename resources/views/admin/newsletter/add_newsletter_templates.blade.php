@extends('admin.layouts.default')

@section('content')

<!-- ckeditor js start here-->
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- ckeditor js end here-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.add_template") }}</span>
		<a href="{{URL::to('admin/news-letter/newsletter-templates')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/news-letter/add-template/','class' => 'mws-form']) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('subject', 'Subject', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('subject', '', ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('constant', 'Constants', ['class' => 'mws-form-label']) }}
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
					{{  Form::label('body', 'Email Body', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::textarea("body",'<p>Hi {USER_NAME}!</p>
						<p>Greeetings of the Day..<br /><br />
							ENTER YOUR TEXT HERE </p>
						<p>&nbsp;</p>

						<p>See you soon on Video Resume.<br />
						&nbsp;</p>

						<p>Video Resume Team</p>
						<br />
						<span style="background-color:rgb(239, 239, 239); font-family:arial,sans-serif; font-size:10px">
							You&#39;re receiving this because you have recently signed up on our website or subscribed our newsletter
						</span>
						<br />
						<span style="color:rgb(34, 34, 34); font-family:arial,sans-serif">You can unsubscribe from the Video Resume&nbsp;</span>
						<span style="color:rgb(34, 34, 34); font-family:arial,sans-serif">newsletter</span>
						<span style="color:rgb(34, 34, 34); font-family:arial,sans-serif">&nbsp;via&nbsp;</span>
						{UNSUBSCRIBE_LINK}<br />
						<br />
						<br />
						&nbsp;', ['class' => 'small','id' => 'body']) }}
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
					
					<a href="{{URL::to('admin/news-letter/add-template')}}" class="btn primary"><i class=\"icon-refresh\"></i> {{ trans('messages.system_management.reset') }}</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>

<script type='text/javascript'>
/* this  function is use for insert constant in ckeditor */
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
