<?php

namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use App\Model\User;
use Crypt;
use App\Model\Userlogin;
use App\Model\EmailAction;
use App\Model\EmailTemplate;
use App\Model\UserCart;
use App\Model\DestinationCountry;
use App\Model\Region;
use App\Model\TripPackage;
use App\Model\BookingEnquiry;
use App\Model\Trip;
use Omnipay\PayPal\Message\ExpressFetchCheckoutRequest;
use Omnipay\Omnipay;
use Omnipay\Common\GatewayFactory;
use App\Model\Booking;
use Illuminate\Contracts\Encryption\DecryptException;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class BookingController extends BaseController {

	public function view(){
		$regionObj = Region::with('regionDescription')->get();
		return View::make('online_booking_view',compact('regionObj'));
	}

	public function SaveBooking(){
		$title = 'Trip Enquiry';
		$allData	=	Input::all();

		$curr_time = date("Y-m-d h:i:s a", time());

		if(!empty($allData)){
			$validator = Validator::make(
				Input::all(),
				array(
					'first_name' 		=> 'required',
					'last_name' 		=> 'required',
					'email' 			=> 'required|email',
					'phone' 			=> 'required',
					'region_id' 		=> 'required',
					'destination_id'	=> 'required',
					'trip_date' 		=> 'required',
					'trip_days' 		=> 'required',
					'trip_city' 		=> 'required',
					'trip_country' 		=> 'required',
					'traveller_number' 	=> 'required',
					'booking_note' 		=> 'required',
				),
				array(
					'first_name.required' 		=> trans('The first name field is required.'),
					'last_name.required' 		=> trans('.The last name field is required.'),
					'email.required' 			=> trans('The email field is required.'),
					'email.email' 				=> trans('The email must be a valid email address.'),
					'phone.required' 			=> trans('.The Phone field is required.'),
					'region_id.required' 		=> trans('The Region name field is required.'),
					'destination_id.required' 	=> trans('.The Destination name field is required.'),
					'trip_date.required' 		=> trans('.The Trip Date field is required.'),
					'trip_days.required' 		=> trans('The Trip Days field is required.'),
					'trip_city.required' 		=> trans('.The Trip City field is required.'),
					'trip_country.required' 	=> trans('The Trip Country field is required.'),
					'traveller_number.required' => trans('.The No. Of Travellers field is required.'),
					'booking_note.required' 	=> trans('The Booking Notes field is required.'),
				)
			);
			
			if ($validator->fails()){	
					$allErrors	= '<ul>';
					foreach ($validator->errors()->all('<li>:message</li>') as $message)
					{
							$allErrors .=  $message; 
					}
					$allErrors .= '</ul>'; 
					$response	=	array(
						'success' 	=> false,
						'errors' 	=> $allErrors
					);
					return  Response::json($response); die;
			}else{
				DB::table('booking_enquiry')->insert(
					array(
						'first_name'		=> Input::get('first_name'),
						'last_name'			=> Input::get('last_name'),
						'email' 			=> Input::get('email'),
						'phone' 			=> Input::get('phone'),
						'region_id' 		=> Input::get('region_id'),
						'country_id'		=> Input::get('destination_id'),
						'trip_date' 		=> date("Y-m-d", strtotime(Input::get('trip_date'))),
						'trip_duration' 	=> Input::get('trip_days'),
						'trip_city' 		=> Input::get('trip_city'),
						'trip_country' 		=> Input::get('trip_country'),
						'traveller_number' 	=> Input::get('traveller_number'),
						'booking_note' 		=> Input::get('booking_note'),
						'created_at'		=> $curr_time,
						'updated_at'		=> $curr_time,
					)
				);
				
				//send email to site admin with user information,to inform that user wants to contact
				
				$emailActions		=  EmailAction::where('action','=','booking_enquiry')->get()->toArray();
				$emailTemplates		=  EmailTemplate::where('action','=','booking_enquiry')->get(array('name','subject','action','body'))->toArray();
				$cons 				=  explode(',',$emailActions[0]['options']);
				$constants 			=  array();
				
				foreach($cons as $key=>$val){
					$constants[] = '{'.$val.'}';
				}
				
				$firstname			=	 Input::get('first_name');
				$lastname			=	 Input::get('last_name');
				$email				=	 Input::get('email');
				$phone 				= 	 Input::get('phone');
				$region				= 	 Input::get('region_id');
				$country			= 	 Input::get('destination_id');
				$trip_date 			= 	 Input::get('trip_date');
				$trip_duration 		= 	 Input::get('trip_days');
				$trip_city 			= 	 Input::get('trip_city');
				$trip_country 		= 	 Input::get('trip_country');
				$traveller_number 	= 	 Input::get('traveller_number');
				$booking_note 		= 	 Input::get('booking_note');
				
				$subject 		=  $emailTemplates[0]['subject'];
				$rep_Array 		=  array($firstname,$lastname,$email,$phone,$region,$country,$trip_date,$trip_duration,$trip_city,$trip_country,$traveller_number,$booking_note); 
				$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
				$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);
				$response	=	array(
					'success' 	=>	'1',
					'errors' 	=>	trans("messages.Thanks for your enquiry.")
				);
				return  Response::json($response); die;	
			}
		}
		$blocks	= array();
		//$blocks	=	Block::getResult('contact-us',array('id','description','block'));
		return View::make('online_booking_view',compact('title'));
		
	}//end TripEnquiry()

	public function BookPackage($trip_id,$package_id){
			
			/*try {

			    $trip_id = Crypt::decrypt($trip_id);
			    $package_id = Crypt::decrypt($package_id);

			} catch (DecryptException $e) {

			    Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
				return Redirect::to('/');

			}	*/

		 	$tripPackageObjCount = TripPackage::where('trip_id','=',$trip_id)
											  ->where('id',$package_id)
											  ->count();
			if($tripPackageObjCount == 0){
				return redirect(URL::to('/'))->withErrors(['package_error'=>'No Such Package is available']);
			}

 			$tripPackageObjCount = TripPackage::with('getTrip')
 											  ->where('trip_id','=',$trip_id)
											  ->where('id',$package_id)
											  ->first();

			$countryId = Trip::select('country_id')
									->where('id','=',$trip_id)
									->first();
			//echo $countryId->country_id;die;

			$countrydetail = DestinationCountry::select('*')
									->where('id','=',$countryId->country_id)
									->first();

			$OtherTrip = Trip::select('*')
									->where('country_id','=',$countryId->country_id)
									->where('id','!=',$trip_id)
									->get();
			//echo "<pre>"; print_r($OtherTrip);die;
			//print_r($tripPackageObjCount);
		    return View::make('package_booking_view',compact('tripPackageObjCount','trip_id','package_id','OtherTrip','countrydetail'));



	}

	public function initiateBooking(){
		echo "<pre>";
		print_r(Input::all());
	}

	public function GetCompanyDetail($region_id) {
		/*$companyDetail = DB::table('destination_country')
								->where('region_id',$region_id)
								->where('active',1)
								->lists('name','id');*/
		$companyDetail =	DestinationCountry::with('destinationDescription')
													->where('region_id',$region_id)
													->where('active',1)
													->select('name', 'id')
													->get();

		return json_encode($companyDetail);
	}
	public function GetRegionTrips($destination_id) {
		/*$tripDetail = DB::table('trips')
								->where('country_id',$destination_id)
								->where('is_active',1)
								->lists('tripname','id');*/
		$tripDetail =Trip::with('tripDescription')
							->where('country_id',$destination_id)
							->where('is_active',1)
							->select('tripname', 'id')
							->get();

		return json_encode($tripDetail);
	}
	public function GetTripDetails($trip_id) {

		$tripDetails = DB::table('trips')
								->select('baseprice','tripdays','tripname','id')
								->where('id',$trip_id)
								->where('is_active',1)
								->first();

		return json_encode($tripDetails);
	}

	public function saveTailoredBooking(){

		$validator = Validator::make(
		    Input::all(),
		    array(
	            'email'            => 'required|email',
	            'first_name'       => 'required',
	            'last_name'        => 'required',
	            'phone'            => 'required',
	             'address'            => 'required',
	            'region_id'          => 'required',
	            'destination_id'     => 'required',
	            'trip_id'          => 'required',
	            'trip_date'       => 'required',
	             'city_departure'=> 'required',
	              'country_departure'=> 'required',
	               'traveller_number'=> 'required',
	             'tailored_trip_days'=> 'required',
	             'tailored_trip_price'=> 'required',
	             'tailored_trip_amount'=> 'required',
	             'tailored_trip_bookingamount'=> 'required',
		        ),
		    array(
		    	'region_id.required'=>"Region is required.",
		    	'destination_id.required'=>"Destination is required.",
		    	'trip_id.required'=>"Trip is required.",
		    	'tailored_trip_price.required'=>'Invalid Price',
		    	'tailored_trip_amount.required'=>'Invalid Total Amount',
		    	'tailored_trip_bookingamount.required'=>'Invalid Booking Amount'

		    )
		    );

		if ($validator->fails()){

		    return Redirect::back()->withErrors($validator)->withInput();

		}

		$firstName 			=	Input::get('first_name');
		$lastName 			=	Input::get('last_name');
		$email 				=	Input::get('email');
		$phone 				=	Input::get('phone');
		$address 				=	Input::get('address');
		$region_id 			=	Input::get('region_id');
		$destination_id 	=	Input::get('destination_id');
		$tripId 			=	Input::get('trip_id');
		$trip_date 			=	Input::get('trip_date');
		$numberOfTraveller 	=	Input::get('traveller_number');
		$city_departure 	=	Input::get('city_departure');
		$country_departure 	=	Input::get('country_departure');
		$booking_note 	    =	Input::get('booking_note');
		$tailored_trip_days 	=	Input::get('tailored_trip_days');
		$tailored_trip_price 	=	Input::get('tailored_trip_price');
		$tailored_trip_amount 	=	Input::get('tailored_trip_amount');
		$tailored_trip_bookingamount 	=	Input::get('tailored_trip_bookingamount');

		//// the code to check if data is tampered.
		$tripObjCheck = Trip::where('id',$tripId)->first();
		if(!$tripObjCheck){
			Session::flash('error',trans("Invalid Trip Id"));
			return Redirect::to('/');
		}
		$trip_baseprice_check = $tripObjCheck->baseprice;
		$trip_tripdays_check = $tripObjCheck->tripdays;
		$trip_amount_check  = $trip_baseprice_check * $numberOfTraveller;
		$trip_bookingamount_check = ($trip_amount_check * 40)/100;

		if($trip_baseprice_check != $tailored_trip_price){
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}

		if($trip_tripdays_check != $tailored_trip_days){
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}

		if($trip_amount_check != $tailored_trip_amount){
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}

		if($trip_bookingamount_check != $tailored_trip_bookingamount){
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}
		//code ends here


		$currency			=	Config::get("Site.currencyCode"); 	
	    $gateway 			= 	new GatewayFactory();
		$gateway 			= 	$gateway->create('PayPal_Express');

		$gateway->setUsername('contact_api1.lastplaces.com');
		$gateway->setPassword('PAB4Q88GNDWD5U3N');
		$gateway->setSignature('A.kyZ9AjzUix-aQ8t951OfOECkv9AIsDHOSN5MZh0uigbQ8eoNkPGJCb');
		$gateway->setTestMode(false);

		$params = array(
			'cancelUrl' 		=> 	WEBSITE_URL,
			'returnUrl' 		=>  WEBSITE_URL.'saveuntailoredbooking',
			'description' 		=>  'Tailored trip',
			'amount' 			=>   $tailored_trip_bookingamount,
			'currency' 			=>  'EUR'
		);

		$bookingDetail = array(
			'first_name' 		=> 	$firstName,
			'last_name' 		=>  $lastName,
			'email' 			=>  $email,
			'phone' 			=>  $phone,
			'address' 			=>  $address,
			'region_id' 			=>  $region_id,
			'destination_id' 			=>  $destination_id,
			'trip_id' 			=>  $tripId,
			'trip_date' 		=>  $trip_date,
			'traveller_number'	=> 	$numberOfTraveller,
			'city_departure'	=> 	$city_departure,
			'country_departure'	=> 	$country_departure,
			'booking_note'	=> 	$booking_note,
			'tailored_trip_days'	=> 	$tailored_trip_days,
			'tailored_trip_price'	=> 	$tailored_trip_price,
			'tailored_trip_amount'	=> 	$tailored_trip_amount,
			'tailored_trip_bookingamount'	=> 	$tailored_trip_bookingamount,
		);

		Session::put('params', $params);
		Session::put('bookingDetail', $bookingDetail);
		Session::save();
		$response = $gateway->purchase($params)->send();
			
		if ($response->isRedirect()) {
			// redirect to offsite payment gateway
			$response->redirect();
		} else {
			// payment failed: display message to customer
			echo $response->getMessage();
		}	

		//echo "<pre>";
		//print_r(Input::all());
	}

	public function saveUntailoredBooking(){

		

		$gateway = new GatewayFactory();
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername('contact_api1.lastplaces.com');
		$gateway->setPassword('PAB4Q88GNDWD5U3N');
		$gateway->setSignature('A.kyZ9AjzUix-aQ8t951OfOECkv9AIsDHOSN5MZh0uigbQ8eoNkPGJCb');
		$gateway->setTestMode(false);

		$response = $gateway->completePurchase(
			array(
				'cancelUrl' 		=> 	WEBSITE_URL,
				'returnUrl' 		=>  WEBSITE_URL.'saveuntailoredbooking',
				'MembershipType'	=> Session::get('params.MembershipType'),
				'description' 		=> Session::get('params.description'),
				'amount' 			=> Session::get('params.amount'),
				'currency' 			=> Session::get('params.currency')
			)
		)->send();
			
		$response = $response->getData(); 

		if(!empty($response) && $response['ACK'] == 'Success'){

			$tripId 									=   Session::get('bookingDetail.trip_id');
			$packageId 									=   0;

			$tripDetail 								=	Trip::where('id',$tripId)->first();
			//user payment detail save
			$objUserPaymentDetail									=	new Booking();
			$objUserPaymentDetail->booking_type						=	2;
			$objUserPaymentDetail->payment_detail					=	serialize($response);
			$objUserPaymentDetail->token							=	$response['TOKEN'];
			$objUserPaymentDetail->time_stamp						=	$response['TIMESTAMP'];
			$objUserPaymentDetail->correlation_id					=	$response['CORRELATIONID'];
			$objUserPaymentDetail->ack								=	$response['ACK'];
			$objUserPaymentDetail->version							=	$response['VERSION'];
			$objUserPaymentDetail->build							=	$response['BUILD'];
			$objUserPaymentDetail->transaction_id					=	$response['PAYMENTINFO_0_TRANSACTIONID'];
			$objUserPaymentDetail->transaction_type					=	$response['PAYMENTINFO_0_TRANSACTIONTYPE'];
			$objUserPaymentDetail->payment_type						=	$response['PAYMENTINFO_0_PAYMENTTYPE'];
			$objUserPaymentDetail->payment							=	1;
			
			$objUserPaymentDetail->trip_id							=	Session::get('bookingDetail.trip_id');
			$objUserPaymentDetail->trip_name						=	$tripDetail->tripname;
			$objUserPaymentDetail->trip_day							=	$tripDetail->tripdays;
			$objUserPaymentDetail->package_id						=	0;
			$objUserPaymentDetail->package_trip_date				=	date("Y-m-d",strtotime(Session::get('bookingDetail.trip_date')));
			$objUserPaymentDetail->package_price					=	Session::get('bookingDetail.tailored_trip_price');
			$objUserPaymentDetail->package_suppliment_price			=	0;
			$objUserPaymentDetail->bookingnote						=	Session::get('bookingDetail.booking_note');

			$objUserPaymentDetail->name								=	Session::get('bookingDetail.first_name').' '.Session::get('bookingDetail.last_name');
			$objUserPaymentDetail->address							=	Session::get('bookingDetail.address');
			$objUserPaymentDetail->email							=	Session::get('bookingDetail.email');
			$objUserPaymentDetail->phone							=	Session::get('bookingDetail.phone');
			$objUserPaymentDetail->number_of_traveller				=	Session::get('bookingDetail.traveller_number');
		    $objUserPaymentDetail->departure_city				=	Session::get('bookingDetail.city_departure');

			$objUserPaymentDetail->departure_country				=	Session::get('bookingDetail.country_departure');


			$objUserPaymentDetail->order_time						=	$response['PAYMENTINFO_0_ORDERTIME'];
			$objUserPaymentDetail->amount							=	$response['PAYMENTINFO_0_AMT'];
		$objUserPaymentDetail->totalamount						=	Session::get('bookingDetail.tailored_trip_amount');
			$objUserPaymentDetail->tax_amount						=	$response['PAYMENTINFO_0_TAXAMT'];
			$objUserPaymentDetail->currency_code					=	$response['PAYMENTINFO_0_CURRENCYCODE'];
			$objUserPaymentDetail->payment_status					=	$response['PAYMENTINFO_0_PAYMENTSTATUS'];
			$objUserPaymentDetail->pending_reason					=	$response['PAYMENTINFO_0_PENDINGREASON'];
			$objUserPaymentDetail->reason_code						=	$response['PAYMENTINFO_0_REASONCODE'];
			$objUserPaymentDetail->paymentmedium					=	"PayPal";
			$objUserPaymentDetail->save();

			// mail code will be here
			$emailActions		=  EmailAction::where('action','=','booking_confirmation')->get()->toArray();
			$emailTemplates		=  EmailTemplate::where('action','=','booking_confirmation')->get(array('name','subject','action','body'))->toArray();
			$cons 				=  explode(',',$emailActions[0]['options']);
			$constants 			=  array();
			
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}

			$firstname = Session::get('bookingDetail.first_name');
			$lastname = Session::get('bookingDetail.last_name');
			$email = Session::get('bookingDetail.email');
			$trip_id = Session::get('bookingDetail.trip_id');
			$destination = $tripDetail->tripname;
			$trip_duration = $tripDetail->tripdays;
			$trip_date = date("Y-m-d",strtotime(Session::get('bookingDetail.trip_date')));
			$traveller_number = Session::get('bookingDetail.traveller_number');
			$trip_city = Session::get('bookingDetail.city_departure');
			$trip_country = Session::get('bookingDetail.country_departure');
			$booking_note = Session::get('bookingDetail.booking_note');

			$trip_price 	= '£ '.Session::get('bookingDetail.tailored_trip_amount');

			//echo $trip_name.'||'.$trip_date;die;

			$subject 		=  $emailTemplates[0]['subject'];
			$rep_Array 		=  array($firstname,$lastname,$email,$destination,$trip_date,$trip_duration,$trip_price,$trip_city,$trip_country,$traveller_number,$booking_note); 
			$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);


			$this->sendMail(Session::get('bookingDetail.email'),'User',$subject,$messageBody);

			$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);
			//mail code ends here


			Session::flash('success',trans("Your booking has been completed successfully."));	
			return  Redirect::to('/');
		}else{		
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}
		echo "<pre>";
		print_r($response);
	}
}
