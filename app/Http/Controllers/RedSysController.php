<?php
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use Excel;
use PDF;
use Crypt;
use App\Model\Trip;
use App\Model\TripPackage;
use App\Model\Booking;
use Omnipay\PayPal\Message\ExpressFetchCheckoutRequest;
use Omnipay\Omnipay;
use Omnipay\Common\GatewayFactory;
use Illuminate\Contracts\Encryption\DecryptException;
use Ssheduardo\Redsys\Facades\Redsys;
use App\Model\EmailAction;
use App\Model\EmailTemplate;


class RedSysController extends BaseController {
/**
 * Function for paypal  payment 
 *
 * @planType as type of plan
 *
 * @return redirect url. 
 */

	public function payWithRedSys(){
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

		//Session::put('params', $params);
		Session::put('bookingDetail', $bookingDetail);
		Session::save();

		try{
          //  echo $key = config('redsys.key');
           // echo "hi";
			$key = "P+TfYiJAVc15LVebTiXw46lPT1tEs/hZ";
			//  echo config('redsys.url_ok');
            //  die();
            Redsys::setAmount($tailored_trip_bookingamount);
            Redsys::setOrder(time());
            Redsys::setMerchantcode('349130591'); //Reemplazar por el código que proporciona el banco
            Redsys::setCurrency('978');
            Redsys::setTransactiontype('0');
            Redsys::setTerminal('1');
            Redsys::setMethod('T'); //Solo pago con tarjeta, no mostramos iupay
            Redsys::setNotification(config('redsys.url_notification')); //Url de notificacion
            Redsys::setUrlOk(config('redsys.url_ok')); //Url OK
            Redsys::setUrlKo(config('redsys.url_ko')); //Url KO             
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setTradeName('Last Places');
            Redsys::setTitular('Pedro Risco');
            Redsys::setProductDescription('Compras varias');
           // Redsys::setEnviroment('test'); //Entorno test
    
            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);
    
            $form = Redsys::executeRedirection();
		}
		catch(Exception $e){
			            echo $e->getMessage();
		}

		return $form;

	}


	public function getOkResponse(){
		
		$response = Input::all();
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');

		 $merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		// print_r($merchantParamsInArray);
		 if(!empty($response)){
			$tripId 									=   Session::get('bookingDetail.trip_id');
			$packageId 									=   0;
			$tripDetail 								=	Trip::where('id',$tripId)->first();

			$objUserPaymentDetail									=	new Booking();
			$objUserPaymentDetail->booking_type						=	2;
			$objUserPaymentDetail->payment_detail					=	serialize($merchantParamsInArray);

			$objUserPaymentDetail->token							=	"null";
			$objUserPaymentDetail->time_stamp						=	urldecode($merchantParamsInArray['Ds_Date'] . " " . $merchantParamsInArray['Ds_Hour']);

			$objUserPaymentDetail->correlation_id					=	"null";
			$objUserPaymentDetail->ack								=	"Success";
			$objUserPaymentDetail->version							=	"RedSys";
			$objUserPaymentDetail->build							=	"RedSys";
			$objUserPaymentDetail->transaction_id					=	$merchantParamsInArray['Ds_Order'];
			$objUserPaymentDetail->transaction_type					=	$merchantParamsInArray['Ds_TransactionType'];
			$objUserPaymentDetail->payment_type						=	"RedSys";
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

			$objUserPaymentDetail->order_time						=	date("Y-m-d H:i:s");
			$objUserPaymentDetail->amount							=	Session::get('bookingDetail.tailored_trip_bookingamount');
		    $objUserPaymentDetail->totalamount						=	Session::get('bookingDetail.tailored_trip_amount');
			$objUserPaymentDetail->tax_amount						=	0;
			$objUserPaymentDetail->currency_code					=	"EUR";
			$objUserPaymentDetail->payment_status					=	"Success";
			$objUserPaymentDetail->pending_reason					=	"null";
			$objUserPaymentDetail->reason_code						=	"null";
			$objUserPaymentDetail->paymentmedium					=	"RedSys";

			$objUserPaymentDetail->save();


			//send email to site admin with user information,to inform that user wants to contact
				
			$emailActions		=  EmailAction::where('action','=','trip_confirmation')->get()->toArray();
			$emailTemplates		=  EmailTemplate::where('action','=','trip_confirmation')->get(array('name','subject','action','body'))->toArray();
			$cons 				=  explode(',',$emailActions[0]['options']);
			$constants 			=  array();
				
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}
			$firstname 				= Session::get('bookingDetail.first_name');
			$lastname 				= Session::get('bookingDetail.last_name');
			$trip_name 				= $tripDetail->tripname;
			$trip_date 				= Session::get('bookingDetail.trip_date');
			$trip_duration 			= $tripDetail->tripdays;
			$trip_price 			= '£ '.Session::get('bookingDetail.tailored_trip_amount');
			$traveller_number 		= Session::get('bookingDetail.traveller_number');
			$payment_method 		= 'Credit Card';
			$email 					= Session::get('bookingDetail.email');
				
			$subject 		=  $emailTemplates[0]['subject'];
			$rep_Array 		=  array($firstname,$lastname,$trip_name,$trip_date,$trip_duration,$trip_price,$traveller_number,$payment_method); 
			$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
			$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);

			$this->sendMail($email,'User',$subject,$messageBody,Config::get("Site.email"));

			Session::flash('success',trans("Your booking has been completed successfully."));	
			return  Redirect::to('/');
		}else{		
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}
		//echo $dsSignature;
	}

	public function getKoResponse(){
		//echo "<pre>";
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');

		 $merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		Session::flash('error',trans("Payment Failed ! Your Transaction was not succesfull."));
		return Redirect::to('/');


	}


	public function packagePayRedsys(){
		$validator = Validator::make(
		    Input::all(),
		    array(
	            'email'            => 'required|email',
	            'first_name'       => 'required',
	            'last_name'        => 'required',
	            'phone'            => 'required',
	            'address'          => 'required',
	            'total_amount'     => 'required',
	            'trip_id'          => 'required',
	            'package_id'       => 'required',
	            'number_travellers'=> 'required'
		        )
		    );

		if ($validator->fails()){

		    return Redirect::back()->withErrors($validator)->withInput();

		}
		$firstName 			=	Input::get('first_name');
		$lastName 			=	Input::get('last_name');
		$email 				=	Input::get('email');
		$phone 				=	Input::get('phone');
		$address 			=	Input::get('address');
		 $totalAmount 		=	Input::get('total_amount');
		
		$tripId 			=	Input::get('trip_id');
		$packageId 			=	Input::get('package_id');
		$numberOfTraveller 	=	Input::get('number_travellers');
		

		$check = $this->validateTotalAmount($numberOfTraveller,$tripId,$packageId);
		if($check != $totalAmount){

			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');

		}
		$currency			=	Config::get("Site.currencyCode"); 	
		$bookingDetail = array(
			'first_name' 		=> 	$firstName,
			'last_name' 		=>  $lastName,
			'email' 			=>  $email,
			'phone' 			=>  $phone,
			'address' 			=>  $address,
			'total_amount' 		=>  $totalAmount,
			'trip_id' 			=>  $tripId,
			'package_id' 		=>  $packageId,
			'no_of_traveller'	=> 	$numberOfTraveller
		);

		Session::put('bookingDetail', $bookingDetail);
		Session::save();

		try{
          //  echo $key = config('redsys.key');
           // echo "hi";
			$key = "P+TfYiJAVc15LVebTiXw46lPT1tEs/hZ";
			//  echo config('redsys.url_ok');
            //  die();
            Redsys::setAmount($totalAmount);
            Redsys::setOrder(time());
            Redsys::setMerchantcode('349130591'); //Reemplazar por el código que proporciona el banco
            Redsys::setCurrency('978');
            Redsys::setTransactiontype('0');
            Redsys::setTerminal('1');
            Redsys::setMethod('T'); //Solo pago con tarjeta, no mostramos iupay
            Redsys::setNotification(config('redsys.url_notification')); //Url de notificacion
            Redsys::setUrlOk(config('redsys.url_ok_package')); //Url OK
            Redsys::setUrlKo(config('redsys.url_ko_package')); //Url KO             
            Redsys::setVersion('HMAC_SHA256_V1');
            Redsys::setTradeName('Last Places');
            Redsys::setTitular('Pedro Risco');
            Redsys::setProductDescription('Compras varias');
            Redsys::setEnviroment('live'); //Entorno test
    
            $signature = Redsys::generateMerchantSignature($key);
            Redsys::setMerchantSignature($signature);
    
            $form = Redsys::executeRedirection();
		}
		catch(Exception $e){
			            echo $e->getMessage();
		}

		return $form;

	}

	public function validateTotalAmount($traveller,$trip,$package){
		$data = TripPackage::where('trip_id',$trip)->where('id',$package)->select('price','supplement')->first();
		$price = $data->price;
		$supplement = $data->supplement;
		if($traveller == 0){
			$amount = 0;
		}else{
			if($traveller == 1){
				$amount = $price + $supplement;
			}else{
				$amount = $price * $traveller;
			}
		}
		return $amount;
	}

	public function getOkResponseFromPackage(){
		$response = Input::all();
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');
		$merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		 if(!empty($response)){
	 		$tripId 									=   Session::get('bookingDetail.trip_id');
			$packageId 									=   Session::get('bookingDetail.package_id');

			$tripDetail 								=	Trip::where('id',$tripId)->first();
			$packageDetail 								=	TripPackage::where('id',$packageId)->first();

			//user payment detail save
			$objUserPaymentDetail									=	new Booking();
			$objUserPaymentDetail->booking_type						=	1;
			$objUserPaymentDetail->payment_detail					=	serialize($response);
			$objUserPaymentDetail->token							=	"null";
			$objUserPaymentDetail->time_stamp						=	urldecode($merchantParamsInArray['Ds_Date'] . " " . $merchantParamsInArray['Ds_Hour']);
			$objUserPaymentDetail->correlation_id					=	"null";
			$objUserPaymentDetail->ack								=	"Success";
			$objUserPaymentDetail->version							=	"RedSys";
			$objUserPaymentDetail->build							=	"RedSys";
			$objUserPaymentDetail->transaction_id					=	$merchantParamsInArray['Ds_Order'];
			$objUserPaymentDetail->transaction_type					=	$merchantParamsInArray['Ds_TransactionType'];
			$objUserPaymentDetail->payment_type						=	"RedSys";
			$objUserPaymentDetail->payment							=	1;
			
			$objUserPaymentDetail->trip_id							=	Session::get('bookingDetail.trip_id');
			$objUserPaymentDetail->trip_name						=	$tripDetail->tripname;
			$objUserPaymentDetail->trip_day							=	$tripDetail->tripdays;
			$objUserPaymentDetail->package_id						=	Session::get('bookingDetail.package_id');
			$objUserPaymentDetail->package_trip_date				=	$packageDetail->trip_date;
			$objUserPaymentDetail->package_price					=	$packageDetail->price;
			$objUserPaymentDetail->package_suppliment_price			=	$packageDetail->supplement;

			$objUserPaymentDetail->name								=	Session::get('bookingDetail.first_name').' '.Session::get('bookingDetail.last_name');
			$objUserPaymentDetail->address							=	Session::get('bookingDetail.address');
			$objUserPaymentDetail->email							=	Session::get('bookingDetail.email');
			$objUserPaymentDetail->phone							=	Session::get('bookingDetail.phone');
			$objUserPaymentDetail->number_of_traveller				=	Session::get('bookingDetail.no_of_traveller');

			$objUserPaymentDetail->order_time						=	date("Y-m-d H:i:s");
			$objUserPaymentDetail->amount							=	Session::get('bookingDetail.total_amount');
			$objUserPaymentDetail->tax_amount						=	0;
			$objUserPaymentDetail->currency_code					=	"EUR";
			$objUserPaymentDetail->payment_status					=	"Success";
			$objUserPaymentDetail->pending_reason					=	"";
			$objUserPaymentDetail->reason_code						=	"";
			$objUserPaymentDetail->paymentmedium					=	"RedSys";
			$objUserPaymentDetail->save();

			//send email to site admin with user information,to inform that user wants to contact
				
			$emailActions		=  EmailAction::where('action','=','trip_confirmation')->get()->toArray();
			$emailTemplates		=  EmailTemplate::where('action','=','trip_confirmation')->get(array('name','subject','action','body'))->toArray();
			$cons 				=  explode(',',$emailActions[0]['options']);
			$constants 			=  array();
				
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}
			$firstname 				= Session::get('bookingDetail.first_name');
			$lastname 				= Session::get('bookingDetail.last_name');
			$trip_name 				= $tripDetail->tripname;
			$trip_date 				= $packageDetail->trip_date;
			$trip_duration 			= $tripDetail->tripdays;
			$trip_price 			= '£ '.Session::get('bookingDetail.total_amount');
			$traveller_number 		= Session::get('bookingDetail.no_of_traveller');
			$payment_method 		= 'Credit Card';
			$email 					= Session::get('bookingDetail.email');
				
			$subject 		=  $emailTemplates[0]['subject'];
			$rep_Array 		=  array($firstname,$lastname,$trip_name,$trip_date,$trip_duration,$trip_price,$traveller_number,$payment_method); 
			$messageBody	=  str_replace($constants, $rep_Array, $emailTemplates[0]['body']);
				 
			$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBody,$email);

			$this->sendMail($email,'User',$subject,$messageBody,Config::get("Site.email"));

			Session::flash('success',trans("Your booking has been completed successfully."));	
			return  Redirect::to('/');
		}else{		
			Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}

	}

	public function getKoResponseFromPackage(){
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');

		 $merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		Session::flash('error',trans("Payment Failed ! Your Transaction was not succesfull."));
		return Redirect::to('/');
	}

	

}// end PaymentController class
