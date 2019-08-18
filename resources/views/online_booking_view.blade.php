@extends('layouts.inner')
@section('content')
<style>
.lastplaces-divider-inner{
	width:100%;
	border-color:#c6b689;
	border-top-width:1px;
	border-bottom-width:1px;
	height:1px;
	border-style: dashed;
}
.lastplaces_seperator{
	padding-top: 20px;
  padding-bottom: 20px;
  font-size: 14px;

  font-style: normal;

  font-weight: 400;
}
.with-errors{
  color:#B22222;
}
</style>
{{ HTML::style('css/datepicker.css') }}
{{ HTML::script('js/bootstrap-datepicker.js') }}
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>{{trans('messages.booking')}}</h1> 
            </div>
        </div>
    </div>
</div>

<div class="container">
	<div class="form_container" style="width:80%;" id="scroll_error_signup">
        <div id="error_div_signup" style="display:none"></div>
        {{ Form::open(['role' => 'form','url' => 'save-tailored-booking','id'=>'bookingenquiry_form','name'=>'bookingenquiry_form','method'=>'post','class' => 'mws-form', 'files' => true]) }}
    		<div class="section_heading">
    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.personalinformation') }}</div>
    		</div>

    		<div class="section_form">
			      <div class="form-group">
              <div class="row">
                  <div class="col-sm-6">
                  {{ Form::text(
                          'first_name', 
                          null,
                          ['class'=>'form-control','placeholder'=>trans('messages.firstname'),'required'=>'required','data-errormessage-value-missing' => 'First Name is required.']
                          ) 
                      }}  
                  </div>
                  <div class="col-sm-6">
                      {{ Form::text(
                          'last_name', 
                          null,
                          ['class'=>'form-control','placeholder'=>trans('messages.lastname'),'required', 'data-errormessage-value-missing' => 'Last Name is required.']
                          ) 
                      }}                                 

                  </div>

                  
              </div>
            </div>

             <div class="form-group">
              <div class="row">
                  <div class="col-sm-6">
                  {{ Form::text(
                          'email', 
                          null,
                          ['class'=>'form-control','placeholder'=>'Email','required'=>'required','data-errormessage-value-missing' => 'Email is required.']
                          ) 
                      }}  
                  </div>
                  <div class="col-sm-6">
                      {{ Form::text(
                          'phone', 
                          null,
                          ['class'=>'form-control','placeholder'=>trans('messages.phone'),'required', 'data-errormessage-value-missing' => 'Phone is required.']
                          ) 
                      }}                                 

                  </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                  <div class="col-sm-12">
                                {{ Form::textarea(
                                    'address', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.address'),'required'=>'required','data-errormessage-value-missing' => 'Address is required','id'=>'address','rows'=>'4']
                                    ) 
                                }}  
                                <div class="help-block with-errors"><?php echo $errors->first('address'); ?></div>  
                      </div>
              </div>
            </div>

					</div>
          <div class="lastplaces_seperator">
          	<div class="lastplaces-divider-inner" ></div>
         </div>

         <div class="section_heading">
	    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.selectdestination') }}</div>
		    	 </div>

    				<div class="section_form">
						  <div class="form-group">
                <div class="row">
                  <div class="col-sm-4">
                    <label for="region_form">{{ trans('messages.region') }}</label>
  							    <select class="form-control" id="region_id" name="region_id" required="">
  							      <option value="">{{ trans('messages.selectregion') }}</option>
                      @foreach($regionObj as $regions)
                      <option value="{{ $regions->regionDescription->foreign_key }}">{{ $regions->regionDescription->source_col_description }}</option>
                      @endforeach
  							    </select>
             <div class="help-block with-errors"><?php echo $errors->first('region_id'); ?></div> 

                  </div>
                  <div class="col-sm-4">
                     <label for="destination_form">{{ trans('messages.destination') }}</label>
    							    <select class="form-control" id="destination_id" name="destination_id" required>
    							      <option value="">{{ trans('messages.selectdestination') }}</option>
    							    </select>   
                      <div class="help-block with-errors"><?php echo $errors->first('destination_id'); ?></div> 
                          
                  </div>
                  <div class="col-sm-4">
                     <label for="destination_form">{{ trans('messages.trips') }}</label>
                      <select class="form-control" id="trip_id" name="trip_id" required>
                        
                      </select>  
          <div class="help-block with-errors"><?php echo $errors->first('trip_id'); ?></div> 
                           
                  </div>
                </div>
              </div>
    				</div>

    				  <div class="lastplaces_seperator">
                      	<div class="lastplaces-divider-inner"></div>
                     </div>


                     <!-- Block for Trip Information  -->
                      <div class="section_heading">
			    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.tripinformation') }}</div>
			    	 </div>

    				<div class="section_form">
						<div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="trip_date">{{ trans('messages.selectdate') }}</label>
							      <input class="form-control" id="trip_date" name="trip_date" size="16" type="text" value="" placeholder="{{ trans('messages.selecttripdate') }}" required="" autocomplete="off">
							      <span class="add-on"><i class="icon-th"></i></span>

							    <script>
							    	var nowTemp = new Date();
									var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
							    	    $('#trip_date').datepicker({
							    	    	format:"dd-mm-yyyy",
							    	    	  onRender: function(date) {
											    return date.valueOf() < now.valueOf() ? 'disabled' : '';
											  }
							    	    })
							    </script>
                  <div class="help-block with-errors"><?php echo $errors->first('trip_date'); ?></div>
                            </div>
                            <div class="col-sm-6">
                                <label for="tripdays">{{ trans('messages.tripduration') }}</label>
                                {{Form::text(
                                    '',
                                    null,
                                    array('class'=>'form-control','data-errormessage-value-missing' => 'Trip Duration is required.','id'=>'trip_days','readonly')
                                    )
                                }}
          <div class="help-block with-errors"><?php echo $errors->first('tailored_trip_days'); ?></div> 

                            </div>                           

                            </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="row">
                            <div class="col-sm-6">
                             <label for="city_departure">{{ trans('messages.cityofdeparture') }}</label>

                                {{ Form::text(
                                    'city_departure', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.cityofdeparture'),'required', 'data-errormessage-value-missing' => 'City Of Departure is required.','id'=>'city_departure']
                                    ) 
                                }}                                 
<div class="help-block with-errors"><?php echo $errors->first('city_departure'); ?></div>
                            </div>

                             <div class="col-sm-6">
                             <label for="country_departure">{{ trans('messages.countryofdeparture') }}</label>

                                {{ Form::text(
                                    'country_departure', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.countryofdeparture'),'required', 'data-errormessage-value-missing' => 'Country Of Departure is required.','id'=>'country_departure']
                                    ) 
                                }}                                 
<div class="help-block with-errors"><?php echo $errors->first('country_departure'); ?></div>

                            </div>
                           
                        </div>
                      </div>


    				  <div class="lastplaces_seperator">
                      	<div class="lastplaces-divider-inner"></div>
                     </div>
                     <!-- Ends here -->

                     <!-- Block for Passenger Information -->
                     <div class="section_heading">
			    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.bookinginformation') }}</div>
			    	 </div>

    				<div class="section_form">
			          <div class="form-group">
                  <div class="row">
                    <div class="col-sm-6">
                       <label for="number_travellers">{{ trans('messages.nooftravellers') }}</label>
      							    <select class="form-control" id="traveller_number" name="traveller_number" required="">
      							      <option value="">{{trans('messages.selectnooftravellers')}}</option>
      							      <option value="1">1</option>
      							      <option value="2">2</option>
      							      <option value="3">3</option>
      							      <option value="4">4</option>
      							      <option value="5">5</option>
      							      <option value="6">6</option>
      							      <option value="7">7</option>
      							      <option value="8">8</option>
      							    </select>
                        <div class="help-block with-errors"><?php echo $errors->first('traveller_number'); ?></div>

                    </div>                         
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                      <div class="col-sm-12">
                      	<label for="booking_notes">{{ trans('messages.bookingnotes') }}</label>

                          {{ Form::textarea(
                              'booking_note', 
                              null,
                              ['class'=>'form-control','placeholder'=>trans('messages.bookingnotes'),'data-errormessage-value-missing' => 'Comment is required.','id'=>'booking_note','rows'=>'4']
                              ) 
                          }}    
                      </div>
                     
                  </div>
                </div>
    				</div>

             

    				  <div class="lastplaces_seperator">
                	<div class="lastplaces-divider-inner"></div>
               </div>

                    <!-- Block for Passenger Information Ends here -->

                    <!--  Block for Calculating the amount -->
                    	<!--  <div class="section_heading">
			    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">Booking Summary</div>
			    	 </div> -->
             <div class="section_heading">
          <div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{ trans('messages.bookingsummary') }}</div>
        </div>

    				<div class="section_form">
  						<div class="form-group">
                  <div class="row">
                

                      <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.tripname')}}: <span id="trip_name_summary"></span></p>
                      </div>
                       <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.tripdays')}}: <span id="trip_days_summary"></span></p>
                          <input type="hidden" name="tailored_trip_days" id="tailored_trip_days" value="" />
                      </div>

                       <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.travellers')}}: <span id="trip_travellers_summary"></span></p>

                      </div>

                       <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.price')}}: <span id="trip_price_summary"></span></p>
                          <input type="hidden" name="tailored_trip_price" id="tailored_trip_price" value="" />
                      </div>

                       <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.totalamount')}}: <span id="trip_totalamount_summary"></span></p>
                          <input type="hidden" name="tailored_trip_amount" id="tailored_trip_amount" value="" />

                      </div>

                       <div class="col-sm-6 bookingsummarydetail">
                          <p>{{trans('messages.bookingamount')}}: <span id="trip_bookingamount_summary"></span></p>
                          <input type="hidden" name="tailored_trip_bookingamount" id="tailored_trip_bookingamount" value="" />

                      </div>

                      <div class="help-block with-errors"><?php echo $errors->first('tailored_trip_price'); ?></div>

                <div class="help-block with-errors"><?php echo $errors->first('tailored_trip_amount'); ?></div>

                <div class="help-block with-errors"><?php echo $errors->first('tailored_trip_bookingamount'); ?></div>
                  </div>
              </div>
    				</div>

    				  <div class="lastplaces_seperator">
                	<div class="lastplaces-divider-inner"></div>
               </div>

                <div class="form-group">
                <div class="row">
                  <div class="col-sm-12">
                    <input type="button" id="paypal_pay_button" value="{{ trans('messages.confirmyourbookingwithpaypal') }}" class="btn btn-primary">

                     <input type="submit" id="common_submit_hidden" value="{{ trans('messages.confirmyourbooking') }}" class="btn btn-primary" style="display:none;">

                     <input type="button" id="redsys_pay" value="{{ trans('messages.confirmyourbookingwithRedsys') }}" class="btn btn-primary">

                     <!-- <input type="submit" id="redsys_pay_hidden" value="{{ trans('messages.confirmyourbooking') }}" class="btn btn-primary" style="display:none;"> -->
                  </div>
                </div>
              </div>
                    <!--  Block for Calculating the amount -->
        {{ Form::close() }}
      	</div>
    	</div>
</div>
<script>
  var tripdays_temp;
  var baseprice_temp;
  var tripname_temp;
  var tripid_temp;
  $(document).ready(function(e){
     
    $("#redsys_pay").click(function(e){
        $("#bookingenquiry_form").attr("action","redsys-pay");
        $("#common_submit_hidden").trigger('click');
    //    $("#bookingenquiry_form").submit();
    });

    $("#paypal_pay_button").click(function(e){
         $("#bookingenquiry_form").attr("action","save-tailored-booking");
         $("#common_submit_hidden").trigger('click');
        // $("#bookingenquiry_form").submit();
    });

    $('#region_id').on('change', function() {
      var region_id = $(this).val();
      $.ajax({
         url: '<?php echo url('online-booking/getCompany'); ?>/'+region_id,
         type: "get",
         dataType: "json",
                success:function(data) {
                    //console.log(data[1].destinationDescription);
                    $('select[name="destination_id"]').empty();
                    $('select[name="destination_id"]').append('<option value="">Please Select Destination');
                    $.each(data, function(e) {
                        var key = data[e].destination_description[0].parent_id;
                        var val = data[e].destination_description[0].source_col_description;
                        $('select[name="destination_id"]').append('<option value="'+ key +'">'+ val +'</option>');
                    });
                }
      })  
    });
    $('#destination_id').on('change', function() {
      var destination_id = $(this).val();
      $.ajax({
         url: '<?php echo url('online-booking/GetRegionTrips'); ?>/'+destination_id,
         type: "get",
         dataType: "json",
                success:function(data) {
                    $('select[name="trip_id"]').empty();
                    $('select[name="trip_id"]').append('<option value="">Please Select Trip');
                    $.each(data, function(e) {
                        var key = data[e].trip_description[0].foreign_key;
                        var val = data[e].trip_description[0].source_col_description;
                        $('select[name="trip_id"]').append('<option value="'+ key +'">'+ val +'</option>');
                    });
                }
      })  
    });
    $('#trip_id').on('change', function() {
      var trip_id = $(this).val();
      $.ajax({
         url: '<?php echo url('online-booking/GetTripDetails'); ?>/'+trip_id,
         type: "get",
         dataType: "json",
                success:function(data) {
                  tripdays_temp = data.tripdays;
                  baseprice_temp = data.baseprice;
                  tripname_temp = data.tripname;
                  tripid_temp = data.id;
                  $("#trip_days").val(tripdays_temp);
                  $("#trip_name_summary").html(tripname_temp);
                  $("#trip_days_summary").html(tripdays_temp);
                  $("#trip_price_summary").html("€" +baseprice_temp);

                   $("#tailored_trip_days").val(tripdays_temp);
                   $("#tailored_trip_price").val(baseprice_temp);

                }
      })  
      $("#traveller_number").val("");
    });

    $("#traveller_number").change(function(e){
        var trip_selection_check = $("#trip_id").val();
        if(trip_selection_check == null || trip_selection_check == ""){
          alert('Please Select Trip First');
          $("#traveller_number").val("");
        }else{
            calculateTheTailoredBooking();
        }
    });
  });

  function calculateTheTailoredBooking(){
   var traveller_count = $("#traveller_number").val();
   var totalamount = traveller_count * baseprice_temp;
   var bookingamount = (totalamount * 40)/100;
   $("#trip_travellers_summary").html(traveller_count);

   $("#tailored_trip_amount").val(totalamount);
   $("#trip_totalamount_summary").html("€" +totalamount);

   $("#tailored_trip_bookingamount").val(bookingamount);
   $("#trip_bookingamount_summary").html("€" +bookingamount);

  }
    $(document).ready(function(){
      /*
    // ajax calling for signup
    var options = {
        beforeSubmit: function(){ 
          $('#error_div_signup').hide();
          $("#submit_signup").button('loading');
          $("#overlay").show();
        },
          success:function(data){
          $("#submit_signup").button('reset');
          $("#overlay").hide();
          if(data.success==1){
              $('#error_div_signup').hide();
              document.getElementById("bookingenquiry_form").reset();
              notice('Booking Enquiry','Booking Enquiry request has been sent successfully.','success');
          }else{
            error_msg   =   '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>'+data.errors+'</div>';
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
            $('#bookingenquiry_form').ajaxForm(options); */
      })
</script>

@include('layouts.main.footer_top')
@stop