<?php
namespace App\Http\Controllers;
use Auth,Blade,Config,Cache,Cookie,DB,File,Hash,Input,Mail,Redirect,Request,Response,Session,URL,View,Validator;
use Excel;
use PDF;
use App\Model\Booking;
use Omnipay\PayPal\Message\ExpressFetchCheckoutRequest;
use Omnipay\Omnipay;
use Omnipay\Common\GatewayFactory;
use Omnipay\PayPal\Message\ProAuthorizeRequest;
use Omnipay\PayPal\Message\ProPurchaseRequest;
use Omnipay\PayPal\Message\ExpressAuthorizeRequest;

class PaymentController extends BaseController {
/**
 * Function for paypal  payment 
 *
 * @planType as type of plan
 *
 * @return redirect url. 
 */
	public function checkout($planType=''){
		// echo "<pre>";
		// print_r(\Input::all()); die;
		$currency			=	Config::get("Site.currencyCode"); 	
	    $gateway 			= new GatewayFactory();
		$gateway 			= $gateway->create('PayPal_Express');

		$gateway->setUsername('contact_api1.lastplaces.com');
		$gateway->setPassword('PAB4Q88GNDWD5U3N');
		$gateway->setSignature('A.kyZ9AjzUix-aQ8t951OfOECkv9AIsDHOSN5MZh0uigbQ8eoNkPGJCb');
		$gateway->setTestMode(false);
		
		$params = array(
			'cancelUrl' 		=> 	WEBSITE_URL.'dashboard',
			'returnUrl' 		=>  WEBSITE_URL.'savemembership',
			'MembershipType'  	=>  'asdf',
			'description' 		=>  ' Months Membership',
			'amount' 			=>  10,
			'currency' 			=>  'USD'
		);

	
		Session::put('params', $params);
		Session::save();
		$formData = array('number' => '4242424242424242', 'expiryMonth' => '12', 'expiryYear' => '2021', 'cvv' => '123');

		$response = $gateway->purchase(array('amount' => '10.00', 'card' => $formData,'currency' => 'USD',	'returnUrl' 		=>  WEBSITE_URL.'savemembership'));

			
		//if ($response->isRedirect()) {
			// redirect to offsite payment gateway
			$response->redirect();
		//} else {
			//// payment failed: display message to customer
			//echo $response->getMessage();
		//}	
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
			//user payment detail save
			$objUserPaymentDetail						=	new Booking();
			$objUserPaymentDetail->payment_detail		=	serialize($response);
			$objUserPaymentDetail->token				=	$response['TOKEN'];
			$objUserPaymentDetail->time_stamp			=	$response['TIMESTAMP'];
			$objUserPaymentDetail->correlation_id		=	$response['CORRELATIONID'];
			$objUserPaymentDetail->ack					=	$response['ACK'];
			$objUserPaymentDetail->version				=	$response['VERSION'];
			$objUserPaymentDetail->build				=	$response['BUILD'];
			$objUserPaymentDetail->transaction_id		=	$response['PAYMENTINFO_0_TRANSACTIONID'];
			$objUserPaymentDetail->transaction_type		=	$response['PAYMENTINFO_0_TRANSACTIONTYPE'];
			$objUserPaymentDetail->payment_type			=	$response['PAYMENTINFO_0_PAYMENTTYPE'];
			$objUserPaymentDetail->order_time			=	$response['PAYMENTINFO_0_ORDERTIME'];
			$objUserPaymentDetail->amount				=	$response['PAYMENTINFO_0_AMT'];
			$objUserPaymentDetail->tax_amount			=	$response['PAYMENTINFO_0_TAXAMT'];
			$objUserPaymentDetail->currency_code		=	$response['PAYMENTINFO_0_CURRENCYCODE'];
			$objUserPaymentDetail->payment_status		=	$response['PAYMENTINFO_0_PAYMENTSTATUS'];
			$objUserPaymentDetail->pending_reason		=	$response['PAYMENTINFO_0_PENDINGREASON'];
			$objUserPaymentDetail->reason_code			=	$response['PAYMENTINFO_0_REASONCODE'];
			$objUserPaymentDetail->save();

			Session::flash('success',trans("messages.Your membership payment received successfully."));	
			return  Redirect::to('/');
		}else{		
			Session::flash('error',trans("messages.Sorry ! Your last action could not be completed due to some error."));
			return Redirect::to('/');
		}
	}// end saveMembership()

}// end PaymentController class
