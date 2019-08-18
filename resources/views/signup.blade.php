@extends('layouts.inner')

@section('content')



<div class="main_content">
	<div class="container">
        <div class="page_title">
            <h1>Register Now</h1>
        </div>
        <div class="row">
        	<div class="col-sm-8" id="scroll_error_signup">
            	<div class="login_form gray_box">
                	<h2>Register Now <span style="display:inline-block">FREE</span></h2>
                	<div id="error_div_signup" style="display:none"></div>
                    {{ Form::open(['role' => 'form','url' => 'registration','id'=>'signup_form','method'=>'post','class'=>'form','snovalidate'=>'novalidate']) }}

                      <!-- <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-6">
                            	{{ Form::text(
									'first_name', 
									null,
									['class'=>'form-control','placeholder'=>"First Name", 'required','data-errormessage-value-missing' => 'First Name is required.']
									) 
								}}	
                            </div>
                            <div class="col-sm-6">
                            	{{ Form::text(
									'last_name', 
									null,
									['class'=>'form-control','placeholder'=>'Last Name', 'required','data-errormessage-value-missing' => 'Last Name is required.']
									) 
								}}	
                            </div>
                        </div>
                      </div> -->
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-12">
                            	{{ Form::email(
									'email', 
									isset($userEmail) ? $userEmail : '',
									['class'=>'form-control','placeholder'=>'Email Address', 'required','data-errormessage-type-mismatch'=>'Email Address is Invalid.','data-errormessage-value-missing' => 'Email Address is required.' ]
									) 
								}}	
                            </div>
                        </div>
                      </div>
                      <div class="form-group" >
                      	<div class="row">
                            <div class="col-sm-6">
                            	{{ Form::password(
									'password', 
									['class'=>'form-control','placeholder'=>'Password','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' =>'Invalid Password', 'required','data-errormessage-value-missing' => 'Password is required.','id'=>'password']
									) 
								}}
                            </div>
                            <div class="col-sm-6">
                            	{{ Form::password(
									'confirm_password', 
									['class'=>'form-control','placeholder'=>'Confirm Password','pattern'=>'.{6,}','data-errormessage-pattern-mismatch' => 'Invalid Confirm Password', 'required','data-errormessage-value-missing' => 'Confirm Password is required.','id'=>'confirm_password']
									) 
								}} 
                            </div>
                        </div>
                      </div>
                      <div id="append_password"></div>
                       <!-- <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-12">

                            	{{ Form::select(
									 'disability',
									 [null => 'Please Select Disability Type'] + $disabilityList,
									 '',
									 ['class'=>'form-control','id'=>'disability_id', 'required']
									) 
								}}


                            	
                            </div>
                        </div>
                      </div> -->

                      <!-- <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-12">
                            	{{ Form::text('address1','',['class'=>'form-control','id'=>'address1','required','data-errormessage-value-missing' => 'Address Line 1 is required.','placeholder'=> "Address Line 1"]) }}
                            </div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-12">
                            	{{ Form::text('address2','',['class'=>'form-control','id'=>'address2','placeholder'=> "Address Line 2"]) }}
                            </div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-6">
                            	{{ Form::text('pincode','',['class'=>'form-control','id'=>'pincode','required','data-errormessage-value-missing' => 'Pincode is required','placeholder'=> "Pincode"]) }}
                            </div>
                            <div class="col-sm-6">
                            	{{ Form::text('phone','',['class'=>'form-control','id'=>'phone','required','data-errormessage-value-missing' => 'Phone is required','placeholder'=> "Phone No."]) }}
                            </div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-6">
                            	{{ Form::text('city','',['class'=>'form-control','id'=>'city','required','data-errormessage-value-missing' => 'City is required','placeholder'=> "City"]) }}
                            </div>
                            <div class="col-sm-6">
                            	{{ Form::text('country','',['class'=>'form-control','id'=>'country','required','data-errormessage-value-missing' => 'Country is required','placeholder'=> "Country"]) }}
                            </div>
                        </div>
                      </div> -->
                      <a href="#" id="generate_password">Generate Password</a>
                      <div class="checkbox">
                        <label>
                        {{ Form::checkbox('terms','1',false,array('id'=>'terms_conditions', 'required'=> 'required')) }}
                           I Agreed to the <a href="{{ URL::to('pages/term-and-condition') }}" target="_blank">Terms &amp; Conditions</a>
                        </label>
                      </div>

                        <?php
                            $attributes = [
                                'data-theme' => 'dark',
                                'data-type' => 'audio',
                            ];
                       ?>
                      {{ Form::captcha($attributes) }}

                      

                      {{
						Form::button(
						'Submit',
						['class'=>'btn btn-success','id'=>'submit_signup','type'=>'submit']
						) 
					}}
                    {{ Form::close() }}
            	</div>
            </div>
        	<div class="col-sm-4">
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
                    <h2>Already have an account <span>Login Now</span></h2>
                    <a class="btn btn-warning btn-register" href="{{ route('login-view')}}">Login Now</a>
            	</div>
            </div>
        </div>
    </div>
</div>

@include('layouts.main.footer_top')



<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places"></script>
<script type="text/javascript">
	
	//function for check valid address
	function gCode(){
			var addr = document.getElementById("location");
			// Get geocoder instance
			var geocoder = new google.maps.Geocoder();
			// Geocode the address
			geocoder.geocode({'address': addr.value}, function(results, status){
				if(status === google.maps.GeocoderStatus.OK && results.length > 0) {
					addr.value = results[0].formatted_address;
					$('#error_div').hide();
					$("#signup_form").submit();
				}else{
					
					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><ul><li>Please enter valid address.</li></ul></div>';
					$('#error_div_signup').hide();
					$('#error_div_signup').html(error_msg);
					
					// animated top scrolling
					$('body, html').animate({scrollTop: 0});
					$('#error_div_signup').show('slow');
					
					
				}
			});
	}
	
	/**
	* function for login user 
	* @param null
	*/
	function loginForm(){
		
		var options = {
			beforeSubmit: function(){ 
				
				$('#password_error').hide();
				$('#email_error').hide();
				$("#submit_login").button('loading');
				$("#overlay").show();
			},
			success:function(data){
					
				$("#submit_login").button('reset');
				$("#overlay").hide();
				
				if(data.success==1){
					window.location.href	=	data.redirect;
				}else{
					
					
					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
					$('#error_div_login').hide();
					$('#error_div_login').html(error_msg);
					
					// top position relative to the document
				//	var pos = $("#scroll_error_signup").offset().top;
	
					// animated top scrolling
				//	$('body, html').animate({scrollTop: 0});
					$('#error_div_login').show('slow');
					
					
					
				}
				return false;
			},
			resetForm:false
		}; 
		// pass options to ajaxForm 
		$('#login_form').ajaxForm(options);
		
	}//end login form
	
	$(document).ready(function(){
	

		initialize();
		
		// ajax calling for signup
		var options = {
			beforeSubmit: function(){ 
				$('#error_div_signup').hide();
				$("#submit_signup").button('loading');
				$("#overlay").show();
			},
			data : {'userEmail' : '<?php echo isset($userEmail) ? $userEmail : ''; ?>'},
			success:function(data){
				$("#submit_signup").button('reset');
				$("#overlay").hide();
				if(data.success==1){
					$('#error_div_signup').hide();
					
					<?php if($userEmail){ ?>
						location.assign(data.url);
					<?php }else { ?>
						document.getElementById("signup_form").reset();
						notice(data.title,data.success_msg,'success');
					<?php } ?>
					
				}else{
					var txt = '';
					if(data.errors.email){
						txt +=  data.errors.email+', ';
					}else if(data.errors.confirm_password){
						txt +=  data.errors.confirm_password+', ';
					}else if(data.errors.password){
						txt +=  data.errors.password;
					}else if(data.errors['g-recaptcha-response']){
						txt += data.errors['g-recaptcha-response'];
					}

					error_msg	=	'<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+txt+'</div>';
					
					$('#error_div_signup').hide();
					$('#error_div_signup').html(error_msg);
					
					// top position relative to the document
					var pos = $("#scroll_error_signup").offset().top;
	
					// animated top scrolling
					$('body, html').animate({scrollTop: 0});
					$('#error_div_signup').show('slow');
				}
				return false;
			},
			resetForm:false
		}; 
		// pass options to ajaxForm 
		$('#signup_form').ajaxForm(options);
	});
	
	// This example displays an address form, using the autocomplete feature
	// of the Google Places API to help users fill in the information.
	var placeSearch, autocomplete,select_first = 1;
	var componentForm = {
		country: 'long_name',
		locality: 'long_name',
	};


	function initialize() {
	  // Create the autocomplete object, restricting the search
	  // to geographical location types.
		
		// FOR SPECIFIC COUNTRY 'US' ONLY 
		var options = {
			
		};
		
		autocomplete = new google.maps.places.Autocomplete(document.getElementById('location'), options);
		/* 	When the user selects an address from the dropdown,
			populate the address fields in the form. */
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			fillInAddress();
		});
	}
	// [START region_fillform]
	function fillInAddress() {
		/* Get the place details from the autocomplete object. */
		var place = autocomplete.getPlace();
		/*	Get each component of the address from the place details
			and fill the corresponding field on the form. */
		for(var i = 0; i < place.address_components.length; i++) {
			var addressType = place.address_components[i].types[0];			
			if(componentForm[addressType]) {
				var val = place.address_components[i][componentForm[addressType]];
				if(addressType == 'country'){
					document.getElementById('country_code').value = place.address_components[i]['short_name'];
				}
				document.getElementById(addressType).value = val;
			}
		}
	}

	$('#generate_password').on('click',function(){
		var html = '<div class="form-group"><div class="row"><div class="col-sm-6"><input type="text" id="suggestPassword" class="form-control"></div><div class="col-sm-3"><input type="button" id="fillNow" class="form-control btn btn-primary" value="Fill Now"></div><div class="col-sm-3"><input type="button" id="cancelPass" class="form-control btn btn-danger" value="Cancel"></div></div></div>';
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
