@extends('layouts.inner')
@section('content')


<div class="main_content">
	<div class="container">
        <div class="page_title">
            <h1>Reset Password</h1>
        </div>
        <div class="row">
        	<div class="col-sm-6 col-lg-4"  id="scroll_error_forgot">
            	<div class="login_form gray_box">
                	<h2>Reset your Password <span></span></h2>
                		<div id="error_div" style="display:none"></div>

                    {{ Form::open(['role' => 'form','url' => 'save_password'.'/'.$validate_string	,'id'=>'reset_password_form','method'=>'post','class'=>'form','ghvalidate'=>'novalidate']) }}

                   
                      <div class="form-group">

                      	{{ Form::password(
					'new_password', 
					['class'=>'form-control','id'=>'password','placeholder'=>'Password','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans('messages.login.front_password_minimum_msg'), 'required','data-errormessage-value-missing' => trans('messages.login.front_password_required_msg')]
					) 
				}}
                      
                      </div>

                       <div class="form-group">

                      	{{ Form::password(
					'new_password_confirmation', 
					['class'=>'form-control','placeholder'=> 'Confirm Password','id'=>'confirm_password','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => trans('messages.login.front_confirm_password_minimum_msg'), 'required','data-errormessage-value-missing' => trans("messages.login.front_confirm_password_required_msg")]
					) 
				}}
                      
                      </div>
                      <div id="append_password"></div>
                      <a href="#" id="generate_password">Generate Password</a>
                	<?php
                            $attributes = [
                                'data-theme' => 'dark',
                                'data-type' => 'audio',
                            ];
                   ?>
                  {{ Form::captcha($attributes) }}


                    {{ Form::button(
					'Submit',
					['class'=>'btn btn-success','id'=>'submit_signup','type'=>'submit']
					) 
				}}

				 	<a class=" fget_pass" href="{{ route('front-sign-up') }}">Cancel</a> 	

               	{{ Form::close() }}
     
            	
            </div></div>
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
		// prepare Options Object 
		var options = {
			beforeSubmit: function() { 
				$('#error_div').hide();
				$("#submit").button('loading');
				$("#overlay").show();
			},
			success:function(data){
				$("#submit").button('reset');
				$("#overlay").hide();
				if(data.success==1){
					notice('Reset Password','Password has been changed successfully.' , 'success');
					setTimeout(function(){ location.assign("{{URL::to('/')}}"); }, 3000);
				}else{

					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors.password+'</div>';
					$('#error_div').hide();
					$('#error_div').html(error_msg);
					
					// animated top scrolling
					$('body, html').animate({scrollTop: 0});
					$('#error_div').show('slow');
				}
				return false;
			},
			resetForm:false
		}; 
		// pass options to ajaxForm 
		$('#reset_password_form').ajaxForm(options);
	
	});

  $('#generate_password').on('click',function(){
    var html = '<div class="form-group"> <div class="row"> <div class="col-sm-12"> <input type="text" id="suggestPassword" class="form-control"> </div></div><div class="row" style="margin-top:12px;"> <div class="col-sm-6"> <input type="button" id="fillNow" class="form-control btn btn-primary" value="Fill Now"> </div><div class="col-sm-6"> <input type="button" id="cancelPass" class="form-control btn btn-danger" value="Cancel"> </div></div></div>';
    var password = randomPassword(10);
    $('#append_password').html('');
    $('#append_password').html(html);
    $('#suggestPassword').val('');
    $('#suggestPassword').val(password);
    //console.log(password);
  });

  $('body').on('click', '#cancelPass', function() {
    $('#password').val('');
    $('#confirm_password').val('');
    $('#suggestPassword').val('');
    $('#append_password').html('');
  });

  $('body').on('click', '#fillNow', function() {
    var pass = $('#suggestPassword').val();
    $('#password').val('');
    $('#confirm_password').val('');
    $('#password').val(pass);
    $('#confirm_password').val(pass);
    $('#suggestPassword').val('');
    $('#append_password').html('');
  });

  $("#password").keypress(function(){

    $('#append_password').html('');
      $('#confirm_password').val('');

  });

    function randomPassword(Mainlength) {
            var passres = '';
            var length = 3;
            charsets = ['0123456789','abcdefghijklmnopqrstuvwxyz','ABCDEFGHIJKLMNOPQRSTUVWXYZ'];

            function gen(length,charsets){
                var pass = "";
                for(var j = 0; j < 3; j++ )
                {
                    //var chars = "abcdefghijklmnopqrstuvwxyz!@#$%^&*()-+<>ABCDEFGHIJKLMNOP1234567890";
                    var chars = charsets[j];
                    
                    for (var x = 0; x < length; x++) {
                        var i = Math.floor(Math.random() * chars.length);
                        pass += chars.charAt(i);
                    }
                }
                return pass;
            }
            return gen(length,charsets);
        }


</script>

@stop
