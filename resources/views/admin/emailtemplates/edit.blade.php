@extends('admin.layouts.default')

@section('content')

<!--- ckeditor js start  here -->
{{ HTML::script('js/bootstrap.js') }}
{{ HTML::script('js/admin/ckeditor/ckeditor.js') }}
<!--- ckeditor js end  here -->

<div class="mws-panel grid_8">
	<div class="mws-panel-header">
		<span> {{ trans("messages.system_management.edit_email_template") }}</span>
		<a href="{{URL::to('admin/email-manager')}}" class="btn btn-success btn-small align">{{ trans("messages.system_management.back") }}  </a>
	</div>
	<div class="mws-panel-body no-padding">
		{{ Form::open(['role' => 'form','url' => 'admin/email-manager/edit-template/'.$emailTemplate->id,'class' => 'mws-form']) }}
		
			<div class="mws-form-inline">
				<div class="mws-form-row">
					{{  Form::label('name', trans("messages.system_management.name"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('name', $emailTemplate->name, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('name'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('subject', trans("messages.system_management.subject") , ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::text('subject', $emailTemplate->subject, ['class' => 'small']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('subject'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row">
					{{  Form::label('action',trans("messages.system_management.action"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('action',$Action_options,$emailTemplate->action, ['class' => 'small','onchange'=>'constant()','readonly']) }}
						<div class="error-message help-inline">
							<?php echo $errors->first('action'); ?>
						</div>
					</div>
				</div>
				<div class="mws-form-row ">
					{{  Form::label('constants', trans("messages.system_management.constants"), ['class' => 'mws-form-label']) }}
					<div class="mws-form-item">
						{{ Form::select('constants', array(),'', ['empty' => 'Select one','class' => 'small']) }}
						
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
						{{ Form::textarea("body",$emailTemplate->body, ['class' => 'small','id' => 'body']) }}
						<span class="error-message help-inline">
							<?php echo $errors->first('body'); ?>
						</span>
					</div>
					<script type="text/javascript">
					/* For Ckeditor */
						
						CKEDITOR.replace( 'body',
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
			<div class="mws-button-row">
				<div class="input" >
					<input type="submit" value='{{ trans("messages.system_management.save") }}' class="btn btn-danger">
					
					<a href="{{URL::to('admin/email-manager/edit-template/'.$emailTemplate->id)}}" class="btn primary"><i class=\"icon-refresh\"></i> {{trans("messages.system_management.reset")  }}</a>
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
	/* this function use insert constant,on click insert variable*/
    
	function InsertHTML() {
		var strUser = document.getElementById("constants").value;
		if(strUser != ''){
			var newStr = '{'+strUser+'}';
			var oEditor = CKEDITOR.instances["body"] ;
			oEditor.insertHtml(newStr) ;	
		}
    }
	
	/* this function use for get constant, define in email template*/
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
