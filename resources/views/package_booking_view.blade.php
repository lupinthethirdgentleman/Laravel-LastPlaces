@extends('layouts.inner')
@section('content')
<?php 
use Illuminate\Support\Facades\Crypt;
	//$encrypter = app('Illuminate\Contracts\Encryption\Encrypter');
	//$message = Crypt::decrypt(Crypt::encrypt('Hello, Universe'));
	   //echo  $message;
	//die;
?>
<style>
.packagedesc{
	background:#f3efe4;
	min-height: 160px;
	padding:5px;
}
.packageprice{
//	margin-left:20px;
	background:#f3efe4;
	min-height: 160px;
		padding:5px;


}
.package_details{
	border-right:6px dashed #f3efe4;
}

.bookingform{
	margin-top:30px;
}
.with-errors{
	color:#B22222;
}
</style>
<div class="page_title">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-4">
                <h1>{{trans('messages.packagebookingpagetitle')}}</h1>
            </div>
        </div>
    </div>
</div>

<div class="container">
	<?php //print_r($errors);?>
	<div class="col-sm-8 package_details">
		<form method="post" action="{{URL::to('/checkout/')}}" name="package_booking_form" id="package_booking_form">

			<div class="col-sm-6 ">
					<div class="packagedesc">
					<h3 style="text-decoration: underline;">{{ $tripPackageObjCount->getTrip->tripname}}</h3>
					<p>{{trans('messages.pleaseselectonpackagebooking')}}</p>
				 </div>
			</div>
			
			<div class="col-sm-6 ">
				<div class="packageprice">
					<h3 style="text-decoration: underline;">{{trans('messages.packagesummery')}}</h3>
					<h4>{{ $tripPackageObjCount->getTrip->tripname}}</h4>
					<h4>{{ $tripPackageObjCount->getTrip->tripdays}} {{trans('messages.days')}}</h4>
					<h4 style="display:inline;">{{trans('messages.price')}} £{{ $tripPackageObjCount->price }}</h4> 
					<span>{{trans('messages.singlesuppliment')}}</span> 
					<h4 style='display:inline;'>£{{ $tripPackageObjCount->supplement}}</h4>
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="col-sm-12 bookingform">
					<div class="form-group">
						<div class="row">
                            <div class="col-sm-12">
							    <select class="form-control" name="number_travellers" id="number_travellers">
							      <option value="" selected>{{trans('messages.selectnooftravellers')}}</option>
							      <option value="1">1</option>
							      <option value="2">2</option>
							      <option value="3">3</option>
							      <option value="4">4</option>
							      <option value="5">5</option>
							      <option value="6">6</option>
							      <option value="7">7</option>
							      <option value="8">8</option>
							    </select>
							    <div class="help-block with-errors"><?php echo $errors->first('number_travellers'); ?></div>
                            </div>
                        </div>
					</div>

					<div class="form-group">
						 <div class="row">
                            <div class="col-sm-6">
                            {{ Form::text(
                                    'first_name', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.firstname'),'required'=>'required','data-errormessage-value-missing' => 'First Name is required.','id'=>'first_name']
                                    ) 
                                }} 
                        	<div class="help-block with-errors"><?php echo $errors->first('first_name'); ?></div> 
                            </div>
                            <div class="col-sm-6">
                                {{ Form::text(
                                    'last_name', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.lastname'),'required', 'data-errormessage-value-missing' => 'Last Name is required.','id'=>'last_name']
                                    ) 
                                }}                                 
                                <div class="help-block with-errors"><?php echo $errors->first('last_name'); ?></div>
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

                      <div class="form-group">
						 <div class="row">
                            <div class="col-sm-12">
                            {{ Form::text(
                                    'email', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>"Email",'required'=>'required','data-errormessage-value-missing' => 'Email is required.','id'=>'email']
                                    ) 
                                }} 
                            <div class="help-block with-errors"><?php echo $errors->first('email'); ?></div> 
                            </div>
                        </div>
					</div>

					<div class="form-group">
						 <div class="row">
                            <div class="col-sm-12">
                                {{ Form::text(
                                    'phone', 
                                    null,
                                    ['class'=>'form-control','placeholder'=>trans('messages.phone'),'required', 'data-errormessage-value-missing' => 'Phone is required.','id'=>'phone']
                                    ) 
                                }}                                 
                                <div class="help-block with-errors"><?php echo $errors->first('phone'); ?></div>
                            </div>
                        </div>
					</div>

			</div>
			<div class="clearfix"></div>

			 <div class="section_heading">
    			<div style="background:#f3efe4; padding:6px 15px; font-size:16px; font-weight:600; color:#3c3b3b; margin-bottom:15px;">{{trans('messages.bookingsummary')}}</div>
    	 	</div>

    	 	<div class="col-sm-12 ">
				<div class="packageprice" style="padding:20px;">
					<p>{{trans('messages.bookingcondition')}}</p>

					<p>{{trans('messages.agreestatement')}} <a href="#">{{trans('messages.bookingconditionspagelinktext')}} </a>. <input id="booking_check" type="checkbox" value="1" name="terms_check"/></p>

					<p>{{trans('messages.totalamount')}} : £ <span id="totalamount_html">1000</span></p>

					<p><button class="btn btn-primary" type="button" disabled="disabled" id="pay_button_precheck">{{ trans('messages.confirmyourbookingwithpaypal') }}</button> 
						<input type="submit" id="common_submit_button" style="display:none" />
					 <button class="btn btn-primary" type="button" disabled="disabled" id="pay_button_precheck_redsys">{{ trans('messages.confirmyourbookingwithRedsys') }}</button></p>
				</div>
			</div>
			<input type="hidden" name="total_amount" id="total_amount" value="0" />
			<input type="hidden" name="trip_id" value="{{$trip_id}}" />
			<input type="hidden" name="package_id" value="{{$package_id}}" />


		</form>
   </div>
    <div class="col-sm-4">
    	
   
    @if(sizeof($OtherTrip)>0)
    @foreach($OtherTrip as $trip_res)
    <div class="region_view-box">
    <div class="row">
     <div class="col-sm-5">
              @if($trip_res['image'] != '' && File::exists(TRIP_IMAGE_ROOT_PATH.$trip_res['img']))
                  <div class="region_view-box-img">
                  
                    <!-- <img src="<?php echo TRIP_IMAGE_URL.$trip_res['image']; ?>" width="550" height="480"  alt=""/> -->
                    <img src="<?php echo WEBSITE_URL.'image.php?width=550px&height=480px&image='.TRIP_IMAGE_URL.$trip_res['image'];?>" alt="" /></a>
                  </div>
                @else
                  <div>
                   
                   {{ HTML::image('img/noimg.png', '', array('width' => '80', 'height' => '80' )) }}
                    </a>
                  </div>
                @endif
     
     </div>
      <div class="col-sm-7">
        <div class="region_view-box-text">  
              <h2>
              <a href="{{ URL::to('trips/'.$countrydetail->slug.'/'.$trip_res['slug']) }}">{{$trip_res['tripname'] }}
              </a></h2>
              <a href="{{ URL::to('trips/'.$countrydetail->slug.'/'.$trip_res['slug']) }}" class="btn btn-primary">{{trans('messages.viewtripdetail')}}</a>
        </div>
      </div>
        
    </div>    
   
    @endforeach
    @else
    <p class="no_trip_found">No Trips Found</p>
    @endif
  </div>
    </div>


</div>

<script>
		function calculateBookingPrice(){
			var price = {{ $tripPackageObjCount->price }};
			var supplement = {{ $tripPackageObjCount->supplement }};

			var travellers = $("#number_travellers").val();

			var amount = 0;
			if(travellers == 0){
				$("#totalamount_html").html(amount);
				$("#total_amount").val(amount);
				//alert('Please select number of travellers');
			}else{
				if(travellers == 1){
					amount = price + supplement;
				}else{
					amount = price * travellers;
				}

				$("#totalamount_html").html(amount);
				$("#total_amount").val(amount);
			}
			

		}

		$(document).ready(function(e){
			calculateBookingPrice();

			$("#number_travellers").change(function(e){
				calculateBookingPrice();
			});

			$("#pay_button_precheck").click(function(e){
				//$("#package_booking_form").submit();
					var amount =$("#total_amount").val();
					var first_name =$("#first_name").val();
					var last_name =$("#last_name").val();
					var booking_notes = $("#booking_notes").val();
					var email = $("#email").val();

					var phone = $("#phone").val();


					if(first_name == "" || last_name == "" || booking_notes == "" || email == "" || phone == ""){
						alert('Please fill all fields in the form');
					}else{
						if(amount > 0){
							$("#package_booking_form").attr("action","{{URL::to('/checkout')}}");
							$("#common_submit_button").trigger('click');
						}else{
							alert('There seems to be some problem with the Package Booking Form. Please fill all details.');
						}

					}
					
			});

			$("#pay_button_precheck_redsys").click(function(e){
					var amount =$("#total_amount").val();
					var first_name =$("#first_name").val();
					var last_name =$("#last_name").val();
					var booking_notes = $("#booking_notes").val();
					var email = $("#email").val();

					var phone = $("#phone").val();


					if(first_name == "" || last_name == "" || booking_notes == "" || email == "" || phone == ""){
						alert('Please fill all fields in the form');
					}else{
						if(amount > 0){
							$("#package_booking_form").attr("action","{{URL::to('/checkout-redsys')}}");
							$("#common_submit_button").trigger('click');
						}else{
							alert('There seems to be some problem with the Package Booking Form. Please fill all details.');
						}

					}
					
			});


			$('#booking_check').click(function(){
			  if($(this).is(':checked'))
			  {
			    // Do some other action
			     $('#pay_button_precheck').prop('disabled', false);
			     $('#pay_button_precheck_redsys').prop('disabled', false);

			  } else {
			  	$('#pay_button_precheck').prop('disabled', true);
   		        $('#pay_button_precheck_redsys').prop('disabled', true);

			  }
			})

		})
</script>

@include('layouts.main.footer_top')
@stop