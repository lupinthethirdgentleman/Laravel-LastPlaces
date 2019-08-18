@extends('layouts.inner')
@section('content')



<div class="main_content">
	<div class="container">
        <div class="page_title">
            <h1>Log In</h1>
        </div>
        <div class="row">
        	<div class="col-sm-6 col-lg-4"  id="scroll_error_forgot">
            	<div class="login_form gray_box">
                	<h2>Forgot Password <span>Reset It Now</span></h2>
                		<div id="error_div_forgot" style="display:none">
                    </div>

                    {{ Form::open(['role' => 'form','url' => 'send_password','id'=>'forgot_password_form','method'=>'post','class'=>'form','ghvalidate'=>'novalidate']) }}

                   
                      <div class="form-group">

                      	{{ Form::email(
						'email', 
						null,
						['class'=>'form-control','placeholder'=>'Email', 'required','data-errormessage-type-mismatch'=>'Invalid Email','data-errormessage-value-missing' => 'Email is required' ] 
						) 
					}}	

                      
                      </div>
                  
                        <?php
                            $attributes = [
                                'data-theme' => 'dark',
                                'data-type' => 'audio',
                            ];
                       ?>
                      {{ Form::captcha($attributes) }}
                      

                      {{  Form::button(
						'Submit',
						['class'=>'btn btn-success ','id'=>'submit_forgot	','type'=>'submit']
						)  }}  

               	{{ Form::close() }}
     
            	</div>
            </div>
        	<div class="col-lg-4 visible-lg">
            	<div class="login_logo">
       	    		<img src="{{ WEBSITE_IMG_URL }}/login_logo.png" alt=""/>
                </div>
            </div>
        	<div class="col-sm-6 col-lg-4">
            	<div class="login_form">
                	<h2>Create an account with us and <span>you will be able to</span></h2>
                    <ul class="bullet_list">
                    	<li><span>See comment</span> of others.</li>
                    	<li><span>Post comment</span> on peoples.</li>
                    	<li>Lorem sit amit <span>amnaahse mardora</span>.</li>
                    	<li>Lorem <span>amit amnaahse</span> mardora.</li>
                    	<li><span>Lorem amit</span> ipsum sit.</li>
                    	<li>Lorem ipsum <span>sit amit</span> amnaahse.</li>
                    </ul>
                    <a class="btn btn-warning btn-register" href="{{ route('front-sign-up')}}">Register Now</a>
            	</div>
            </div>
        </div>
    </div>
</div>

@include('layouts.main.footer_top')

<script type="text/javascript">
	
	$(document).ready(function() {
		// ajax calling for forget password 
		var optionsforget = { 
			beforeSubmit: function() { 
				$('#error_div_forgot').hide();
				$("#overlay").show();
			},
			success:function(data){ 
				$("#overlay").hide();
				if(data.success==1){
					document.getElementById("forgot_password_form").reset();
					notice('Forget Password','Reset password link has been sent to your registered email id.' ,'success');
				}else{
					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
					$('#error_div_forgot').hide();
					$('#error_div_forgot').html(error_msg);
					
					// top position relative to the document
					var pos = $("#scroll_error_forgot").offset().top;
	
					// animated top scrolling
					$('body, html').animate({scrollTop: 0});
					$('#error_div_forgot').show('slow');
				}
				return false;
			},
			resetForm:false
		}; 
		// pass options to ajaxForm 
		$('#forgot_password_form').ajaxForm(optionsforget);
	});
</script>

@stop
