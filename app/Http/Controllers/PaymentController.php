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
use App\Model\EmailAction;
use App\Model\EmailTemplate;

class PaymentController extends BaseController {
/**
 * Function for paypal  payment 
 *
 * @planType as type of plan
 *
 * @return redirect url. 
 */
	public function checkout($planType=''){

		//validation
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

		/*try {

		    $decryptedTripId = Crypt::decrypt(Input::get('trip_id'));
		    $decryptedPackageId = Crypt::decrypt(Input::get('package_id'));

		} catch (DecryptException $e) {

		    Session::flash('error',trans("Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');

		}*/
		
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
	    $gateway 			= 	new GatewayFactory();
		$gateway 			= 	$gateway->create('PayPal_Express');

		$gateway->setUsername('contact_api1.lastplaces.com');
		$gateway->setPassword('PAB4Q88GNDWD5U3N');
		$gateway->setSignature('A.kyZ9AjzUix-aQ8t951OfOECkv9AIsDHOSN5MZh0uigbQ8eoNkPGJCb');
		$gateway->setTestMode(false);
		
		$params = array(
			'cancelUrl' 		=> 	WEBSITE_URL,
			'returnUrl' 		=>  WEBSITE_URL.'savemembership',
			'description' 		=>  'trip',
			'amount' 			=>   $totalAmount,
			'currency' 			=>  'EUR'
		);

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
	}//end checkout()
	
	/**
	 * Function for save user membership
	 *
	 * @param null
	 *
	 * @return view page. 
	 */
	public function saveMembership(){
		$gateway = new GatewayFactory();
		$gateway = Omnipay::create('PayPal_Express');
		$gateway->setUsername('contact_api1.lastplaces.com');
		$gateway->setPassword('PAB4Q88GNDWD5U3N');
		$gateway->setSignature('A.kyZ9AjzUix-aQ8t951OfOECkv9AIsDHOSN5MZh0uigbQ8eoNkPGJCb');
		$gateway->setTestMode(false);

		$response = $gateway->completePurchase(
			array(
				'cancelUrl' 		=> 	WEBSITE_URL,
				'returnUrl' 		=>  WEBSITE_URL.'savemembership',
				'MembershipType'	=> Session::get('params.MembershipType'),
				'description' 		=> Session::get('params.description'),
				'amount' 			=> Session::get('params.amount'),
				'currency' 			=> Session::get('params.currency')
			)
		)->send();
			
		$response = $response->getData(); 
		
		if(!empty($response) && $response['ACK'] == 'Success'){


			$tripId 									=   Session::get('bookingDetail.trip_id');
			$packageId 									=   Session::get('bookingDetail.package_id');

			$tripDetail 								=	Trip::where('id',$tripId)->first();
			$packageDetail 								=	TripPackage::where('id',$packageId)->first();

			//user payment detail save
			$objUserPaymentDetail									=	new Booking();
			$objUserPaymentDetail->booking_type						=	1;
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
			$objUserPaymentDetail->package_id						=	Session::get('bookingDetail.package_id');
			$objUserPaymentDetail->package_trip_date				=	$packageDetail->trip_date;
			$objUserPaymentDetail->package_price					=	$packageDetail->price;
			$objUserPaymentDetail->package_suppliment_price			=	$packageDetail->supplement;

			$objUserPaymentDetail->name								=	Session::get('bookingDetail.first_name').' '.Session::get('bookingDetail.last_name');
			$objUserPaymentDetail->address							=	Session::get('bookingDetail.address');
			$objUserPaymentDetail->email							=	Session::get('bookingDetail.email');
			$objUserPaymentDetail->phone							=	Session::get('bookingDetail.phone');
			$objUserPaymentDetail->number_of_traveller				=	Session::get('bookingDetail.no_of_traveller');

			$objUserPaymentDetail->order_time						=	$response['PAYMENTINFO_0_ORDERTIME'];
			$objUserPaymentDetail->amount							=	$response['PAYMENTINFO_0_AMT'];
			$objUserPaymentDetail->tax_amount						=	$response['PAYMENTINFO_0_TAXAMT'];
			$objUserPaymentDetail->currency_code					=	$response['PAYMENTINFO_0_CURRENCYCODE'];
			$objUserPaymentDetail->payment_status					=	$response['PAYMENTINFO_0_PAYMENTSTATUS'];
			$objUserPaymentDetail->pending_reason					=	$response['PAYMENTINFO_0_PENDINGREASON'];
			$objUserPaymentDetail->reason_code						=	$response['PAYMENTINFO_0_REASONCODE'];
			$objUserPaymentDetail->paymentmedium					=	"PayPal";
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
			$trip_price 			= '£ '.$response['PAYMENTINFO_0_AMT'];
			$traveller_number 		= Session::get('bookingDetail.no_of_traveller');
			$payment_method 		= 'PayPal';
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
	}// end saveMembership()

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

}// end PaymentController class
