@extends('admin.layouts.default')

@section('content')

<!-- ckeditor js start here-->
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!-- ckeditor js  end here-->

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ 'Add Email Template' }}</span>
		<a href="{{URL::to('admin/email-manager')}}" class="btn btn-success btn-small align">{{ 'Back To Email Template' }} </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/email-manager/add-template','class' => 'mws-form']) }}
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('name', 'Name', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('name', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('name'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('subject', 'Subject', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('subject', null, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('action', 'Action', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('action', $Action_options,'', ['class' => 'small','onchange'=>'constant()']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('action'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('constants', 'Constants', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('constants', array(),'', ['empty' => 'Select one','class' => 'small']) }}
						
						<span style = "padding-left:20px;padding-top:0px; valign:top">
							<a onclick = "return InsertHTML()" href="javascript:void(0)" class="btn  btn-success no-ajax"><i class="icon-white "></i>{{ 'Insert Variable' }} </a>
						</span>
						<div class="error-message help-inline">
							<?php echo $errors->first('constants'); ?>
						</div>
						
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('body', 'Email Body', ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::textarea("body",'', ['class' => 'small','id' => 'body']) }}
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
					<input type="submit" value="Save" class="btn btn-danger">
					
					<a href="{{URL::to('admin/email-manager/add-template')}}" class="btn primary"><i class=\"icon-refresh\"></i> Reset</a>
				</div>
			</div>
		{{ Form::close() }}
	</div>    	
</div>
<?php  $constant = ''; ?>
<script type='text/javascript'>
var myText = '<?php  echo $constant; ?>';
	$(function(){
		constant();
	});
	/* this function use for insert constant in ckeditor*/
    function InsertHTML() {
		
		var strUser = document.getElementById("constants").value;
		
		if(strUser != ''){
			var newStr = '{'+strUser+'}';
			var oEditor = CKEDITOR.instances["body"] ;
			oEditor.insertHtml(newStr) ;	
		}
    }
	/* this function use for get define constant in email template*/
	function constant() {
		var constant = document.getElementById("action").value;
			$.ajax({
				url: "<?php echo URL::to('admin/email-manager/get-constant')?>",
				type: "POST",
				data: { constant: constant},
				dataType: 'json',
				success: function(r){
					
					$('#constants').empty();
					$('#constants').append( '<option value="">-- Select One --</option>' );
					$.each(r, function(val, text) {
						var sel ='';
						if(myText == text)
						 {
						   sel ='selected="selected"';
						 }
						 
						$('#constants').append( '<option value="'+text+'" '+sel+'>'+text+'</option>');
					});	
			   }
			});
		return false; 
	}
</script>

@stop