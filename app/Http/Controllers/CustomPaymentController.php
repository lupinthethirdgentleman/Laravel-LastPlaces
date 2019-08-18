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
use App\Model\CustomPayment;
use Illuminate\Contracts\Encryption\DecryptException;
use Ssheduardo\Redsys\Facades\Redsys;


/**
 * Login Controller
 *
 * Add your methods in the class below
 *
 * This file will render views from views
 */ 
 
class CustomPaymentController extends BaseController {

	public function view(){
		return View::make('custom_payment');
	}

	public function savePayment(){
			$validator = Validator::make(
		    Input::all(),
		    array(
		    	'email'=>'required',
	            'custom_amount'=> 'required',
	            'custom_description'=> 'required'
		        ),
		    array(
		    	'email.required'=>"Email is required.",
		    	'custom_amount.required'=>"Amount is required.",
		    	'custom_description.required'=>"Description is required."
		    )
		    );

		    if ($validator->fails()){
		    return Redirect::back()->withErrors($validator)->withInput();
			}

			$email 			=	Input::get('email');
			$amount 		=	Input::get('custom_amount');
			$description 	=	Input::get('custom_description');

		$currency			=	Config::get("Site.currencyCode"); 
		$paymentDetail = array(
			'email'			=>	$email,
			'amount' 		=> 	$amount,
			'description' 	=>  $description,
		);

		//Session::put('params', $params);
		Session::put('paymentDetail', $paymentDetail);
		Session::save();

		try{
          //  echo $key = config('redsys.key');
           // echo "hi";
			$key = "P+TfYiJAVc15LVebTiXw46lPT1tEs/hZ";
			//  echo config('redsys.url_ok');
            //  die();
            Redsys::setAmount($amount);
            Redsys::setOrder(time());
            Redsys::setMerchantcode('349130591'); //Reemplazar por el código que proporciona el banco
            Redsys::setCurrency('978');
            Redsys::setTransactiontype('0');
            Redsys::setTerminal('1');
            Redsys::setMethod('T'); //Solo pago con tarjeta, no mostramos iupay
            Redsys::setNotification(config('redsys.url_notification')); //Url de notificacion
            Redsys::setUrlOk(config('custom-redsys.url_ok')); //Url OK
            Redsys::setUrlKo(config('custom-redsys.url_ko')); //Url KO             
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


	public function getOkResponse(){
		
		$response = Input::all();
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');

		 $merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		// print_r($merchantParamsInArray);
		 if(!empty($response)){
			$objUserPaymentDetail									=	new CustomPayment();
			$objUserPaymentDetail->payment_detail					=	serialize($merchantParamsInArray);
			$objUserPaymentDetail->time_stamp						=	urldecode($merchantParamsInArray['Ds_Date'] . " " . $merchantParamsInArray['Ds_Hour']);
			$objUserPaymentDetail->ack								=	"Success";
			$objUserPaymentDetail->transaction_id					=	$merchantParamsInArray['Ds_Order'];
			$objUserPaymentDetail->payment_type						=	"RedSys";
			$objUserPaymentDetail->email							=	Session::get('paymentDetail.email');
			$objUserPaymentDetail->amount							=	Session::get('paymentDetail.amount');
		    $objUserPaymentDetail->description						=	Session::get('paymentDetail.description');
			$objUserPaymentDetail->currency_code					=	"EUR";
			$objUserPaymentDetail->payment_status					=	"Success";
			//$objUserPaymentDetail->save();

			//send email to site admin with user information,to inform that user wants to contact
				
			$emailActions		=  EmailAction::where('action','=','payment_confirmation')->get()->toArray();
			$emailTemplates		=  EmailTemplate::where('action','=','payment_confirmation')->get(array('name','subject','action','body'))->toArray();
			$cons 				=  explode(',',$emailActions[0]['options']);
			$constants 			=  array();
				
			foreach($cons as $key=>$val){
				$constants[] = '{'.$val.'}';
			}
			$recipient_name_ad 		= "Admin";
			$recipient_name_cust 	= "Customer";
			$email 					= Session::get('paymentDetail.email');
			$amount 				= Session::get('paymentDetail.amount');
			$description 			= Session::get('paymentDetail.description');
				
			$subject 			=  $emailTemplates[0]['subject'];
			$rep_Array_ad 		=  array($email,$recipient_name_ad,$amount,$description);
			$rep_Array_cust		=  array($email,$recipient_name_cust,$amount,$description); 
			$messageBodyAd		=  str_replace($constants, $rep_Array_ad, $emailTemplates[0]['body']);
			$messageBodyCust	=  str_replace($constants, $rep_Array_cust, $emailTemplates[0]['body']);
				 
			$this->sendMail(Config::get("Site.email"),'Admin',$subject,$messageBodyAd,Config::get("Site.email"));
			$this->sendMail($email,'Customer',$subject,$messageBodyCust,Config::get("Site.email"));

			Session::flash('success',trans("Your Payment has been completed successfully."));	
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
	            'custom_amount'=> 'required',
	            'custom_description'=> 'required'
		        )
		    );

		if ($validator->fails()){

		    return Redirect::back()->withErrors($validator)->withInput();

		}
		$amount 		=	Input::get('custom_amount');
		$description 	=	Input::get('custom_description');
		

		
		$currency			=	Config::get("Site.currencyCode"); 	
		$paymentDetail = array(
			'amount' 		=> 	$amount,
			'description' 		=>  $description
		);

		Session::put('paymentDetail', $paymentDetail);
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
            Redsys::setUrlOk(config('custom-redsys.url_ok_package')); //Url OK
            Redsys::setUrlKo(config('custom-redsys.url_ko_package')); //Url KO             
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

	public function getOkResponseFromPackage(){
		$response = Input::all();
		$dsSignatureVersion = Input::get('Ds_SignatureVersion');
		$dsMerchantParams = Input::get('Ds_MerchantParameters');
		$dsSignature = Input::get('Ds_Signature');
		$merchantParamsInArray =  Redsys::getMerchantParameters($dsMerchantParams);

		 if(!empty($response)){
	 		$objUserPaymentDetail									=	new CustomPayment();
			$objUserPaymentDetail->payment_detail					=	serialize($merchantParamsInArray);
			$objUserPaymentDetail->time_stamp						=	urldecode($merchantParamsInArray['Ds_Date'] . " " . $merchantParamsInArray['Ds_Hour']);
			$objUserPaymentDetail->ack								=	"Success";
			$objUserPaymentDetail->transaction_id					=	$merchantParamsInArray['Ds_Order'];
			$objUserPaymentDetail->payment_type						=	"RedSys";
			$objUserPaymentDetail->amount							=	Session::get('paymentDetail.amount');
		    $objUserPaymentDetail->description						=	Session::get('paymentDetail.description');
			$objUserPaymentDetail->currency_code					=	"EUR";
			$objUserPaymentDetail->payment_status					=	"Success";
			$objUserPaymentDetail->save();

			Session::flash('success',trans("Your payment has been completed successfully."));	
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
}
