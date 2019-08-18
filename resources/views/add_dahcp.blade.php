@extends('layouts.inner')



@section('content')



<div class="main_content">
	<div class="container">
        <div class="page_title">
            <h1>Add new DA/HCP</h1>
        </div>
        <div class="row">
        	<div class="col-sm-12" id="scroll_error_signup">
            	<div class="login_form gray_box">
                	<h2>Add new <span style="display:inline-block">DA/HCP</span> now</h2>
                  <div id="error_div_signup" style="display:none"></div>
                    <form method="post" action="{{ URL::to('save_dahcp'); }}" accept-charset="UTF-8" role="form" id="signup_form" class="form" snovalidate="novalidate">
                      <input name="_token" type="hidden" value="IEDpA2wHhKNLg4QmuSUe4KFZSqRcY5ueakp1MuCq">
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-4"><input type="text" class="form-control" name="first_name" id="firstname" placeholder="First Name" required="required" data-errormessage-value-missing="First Name is required."></div>
                            <div class="col-sm-4"><input type="text" class="form-control" name="middle_name" id="middlename" placeholder="Middle Name"></div>
                            <div class="col-sm-4"><input type="text" class="form-control" name="last_name" id="lastname" placeholder="Last Name" required="required" data-errormessage-value-missing="First Name is required."></div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <!-- <div class="col-sm-6"><input type="email" name="email" class="form-control" id="register_email" placeholder="Email" required="required" data-errormessage-value-missing="Email is required."></div> -->
                            <div class="col-sm-12"><input type="text" name="profession" class="form-control" id="profession" placeholder="Profession" required="required" data-errormessage-value-missing="Profession is required."></div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-6">
                              <!-- <input type="text" class="form-control" id="company" placeholder="Company Name"> -->
                              {{ Form::select(
                                 'company_id',
                                 [null => 'Please Select Company Name'] + $company_list,
                                 '',
                                 ['class'=>'form-control','id'=>'company_id', 'required']
                                ) 
                              }}
                            </div>
                            <div class="col-sm-6">
                              <!-- <input type="text" class="form-control" id="company_location" placeholder="Company Location"> -->
                              {{ Form::select(
                                 'location',
                                 [null => 'Please Select Company Location'],
                                 '',
                                 ['class'=>'form-control','id'=>'location_id', 'required']
                                ) 
                              }}
                            </div>
                        </div>
                      </div>
                      <div class="form-group">
                      	<div class="row">
                            <div class="col-sm-6"><label>Upload Your Image</label><input type="file" name="dahcp_image" class="form-control" id="register_photo"></div>
                        </div>
                      </div>
                      <!-- <div class="checkbox">
                        <label>
                          <input type="checkbox"> Subscribe for the Newsletters.
                        </label>
                      </div> -->
                      <button type="submit" id="submit_signup" class="btn btn-success">Submit</button>
                    </form>
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
          
          error_msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button><ul><li>Please enter valid address.</li></ul></div>';
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
          window.location.href  = data.redirect;
        }else{
          
          
          error_msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
          $('#error_div_login').hide();
          $('#error_div_login').html(error_msg);
          
          // top position relative to the document
        //  var pos = $("#scroll_error_signup").offset().top;
  
          // animated top scrolling
        //  $('body, html').animate({scrollTop: 0});
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

    //Get Locations
    $('#company_id').on('change', function() {
      var company_id = $(this).val();
      $.ajax({
         url: '<?php echo url('dahcp/getlocation'); ?>/'+company_id,
         type: "get",
         dataType: "json",
                success:function(data) {
                    $('select[name="location"]').empty();
                    $('select[name="location"]').append('<option value="">Please Select Company Location</option>');
                    $.each(data, function(key, value) {

                        $('select[name="location"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
      })  
    });

    var company_id = $('#company_id').val();
    $.ajax({
       url: '<?php echo url('dahcp/getlocation'); ?>/'+company_id,
       type: "get",
       dataType: "json",
            success:function(data) {
                $('select[name="location"]').empty();
                $('select[name="location"]').append('<option value="">Please Select Company Location</option>');
                $.each(data, function(key, value) {

                    $('select[name="location"]').append('<option value="'+ key +'" selected="selected">'+ value +'</option>');
                });
            }
    })


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
          error_msg = '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
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
    /*  When the user selects an address from the dropdown,
      populate the address fields in the form. */
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
      fillInAddress();
    });
  }
  // [START region_fillform]
  function fillInAddress() {
    /* Get the place details from the autocomplete object. */
    var place = autocomplete.getPlace();
    /*  Get each component of the address from the place details
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
</script>

@stop