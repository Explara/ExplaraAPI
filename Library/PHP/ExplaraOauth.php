<?php 

/*
 *Explara API Library Version 1.7
 *Support Email : support@explara.com
 *Website URL   : http://developers.explara.com
 * 
 * NOTE : 
 * Only 10 create event requests are allowed in a day
 * Only 200 create ticket requests are allowed in a day
 * Only 2000 create discount requests are allowed in a day
 * 
 * Only 30 fetch details request are allowed in an hour (combination of get event, tickets, discounts, report & event list)
 * 
 * All rights reserved (c) Explara.com
 */
if (!function_exists('curl_init')) {
	die('Explara API for PHP requires the PHP cURL extension.');
}
require_once('ExplaraConfig.php');
class Explara {
	/**
	 * Version.
	 */
	const VERSION = '1.7';
	
	private $accessToken;
	private $clientId;
	private $clientSecret;
	private $apiUrl;
	private $tokenUrl;
	private $publisherKey;
	private $bookingUrl;
	private $privateKey;
	
	function __construct() {
		$this->load_keys();
	}
	
	private function load_keys(){	
		$this->accessToken				= accessToken;
		$this->clientId					= clientId;
		$this->clientSecret				= clientSecret;
		$this->publisherKey				= explaraPublisherKey;
		$this->bookingKey				= explaraBookingKey;
		$this->secretSoap				= secretSoap;
		
		$this->apiUrl					= 'https://em.explara.com/api/resource/';
		$this->tokenUrl					= 'https://em.explara.com/a/account/oauth/';
		$this->bookingUrl				= 'https://em.explara.com/booking';
		$this->confirm					= 'https://em.explara.com/api/resource/pay';
	}
	
	public function createEvent($data){
		$response	= $this->initRequest($data,'create');
		return $response;
	}
	
	public function updateEvent($data){
		$response	= $this->initRequest($data,'update');
		return $response;
	}
	
	public function createTicket($data){
		$response	= $this->initRequest($data,'add-ticket');
		return $response;
	}
	
	public function updateTicket($data){
		$response	= $this->initRequest($data,'update-ticket');
		return $response;
	}
	
	public function createDiscount($data){
		$response	= $this->initRequest($data,'add-discount');
		return $response;
	}
	
	public function updateDiscount($data){
		$response	= $this->initRequest($data,'update-discount');
		return $response;
	}
	
	public function getEvent($eventId){
		$data		= array('eventId'=>$eventId);				
		$response	= $this->initRequest($data,'get-event');
		return $response;
	}
	
	public function getTickets($eventId){
		$data		= array('eventId'=>$eventId);
		$response	= $this->initRequest($data,'get-tickets');
		return $response;
	}
	
	public function getEventList(){
		$response	= $this->initRequest(NULL,'get-all-events');
		return $response;
	}
	
	public function getReport($eventId){
		$data		= array('eventId'=>$eventId);
		$response	= $this->initRequest($data,'get-report');
		return $response;
	}
	
	public function getAttendee($eventId,$fromRecord,$toRecord){
		$data		= array('eventId'=>$eventId,'fromRecord'=>$fromRecord,'toRecord'=>$toRecord);
		$response	= $this->initRequest($data,'attendee-list');
		return $response;
	}
	
	//For Booking
	public function doBooking($data){
	?>
			<form name="formBooking" id="formBooking" style="display:none;" action="<?php echo $this->bookingUrl ?>" method="post">
				<input type="text" name="bookingKey" value="<?php echo $this->bookingKey ?>" />
				<?php foreach($data as $key=>$value) { ?>
				<input type="text" name="<?php echo $key ?>" value="<?php echo $value ?>" />
				<?php } ?>
			</form>
			<script type="text/javascript"> 
				window.onload=function(){
	            	document.getElementById("formBooking").submit();
				}
	       </script>
	   <?php
	}
	//validate response
	public function getResponse($response){
		$decryptString			= base64_decode($response);
		$param					= explode('|',$decryptString);
		$orderNo				= $param[0];
		$status					= $param[1];
		$amount					= $param[2];
		$secureCode				= $param[3];
		if(md5($this->accessToken) != $secureCode){
			return array("status"=>'error','message'=>'E022');
		}else {
			return array("status"=>'success','orderNo'=>$orderNo,'status'=>$status,'amount'=>$amount);
		}
	}
	
	public function getBookingList($fromDate,$toDate){
		$data		= array('fromDate'=>$fromDate,'toDate'=>$toDate,'bookingKey'=>$this->bookingKey);
		$response	= $this->initRequest($data,'get-booking-list');
		return $response;
	}
		
	//For Publisher
	public function getEventListForPublisher($category){
		$data		= array('publisherKey'=>$this->publisherKey,'category'=>$category);
		$response	= $this->initRequest($data,'event-list-publisher');
		return $response;
	}
	
	public function ticketListPublisher($eventId){
		//echo "there"; die;
		$data		= array('publisherKey'=>$this->publisherKey,'eventId'=>$eventId);
		$response	= $this->initRequest($data,'ticket-list-publisher');
		return $response;
	}
	
	public function getCart($data){
		$response	= $this->initSalesRequest($data,'check-cart');
		return $response;
	}
	
	public function confirmCashOrder($data){
		$response	= $this->initSalesRequest($data,'confirm-cash-order');
		return $response;
	}
	
	public function checkApiOrder($data){
		//echo "<br><pre>"; print_r($data); 
		$orderNo		= $data['orderNo'];
		$status			= $data['status'];
		$responseHash	= $data['hash'];
		$hashString		= $data['orderNo'].'|'.$status.'|'.$this->secretSoap;
		$hash			= strtolower(hash('sha512', $hashString));
		//echo $hash; die;
		if($hash == $responseHash){
			return 'valid';
		}else {
			return 'invalid';
		}
	}
	
	public function pay($data){
		$hash_string 	= $this->secretSoap.'|'.$data['orderNo'].'|'.$data['returnUrl'];
		$hash			= strtolower(hash('sha512', $hash_string));
			?>
				<form name="formPay" id="formPay" style="display:none;" action="<?php echo $this->confirm ?>" method="post">
					<input type="text" name="hash" value="<?php echo $hash ?>" />
					<?php foreach($data as $key=>$value) { ?>
					<input type="text" name="<?php echo $key ?>" value="<?php echo $value ?>" />
					<?php } ?>
				</form>
				<script type="text/javascript"> 
					window.onload=function(){
		            	document.getElementById("formPay").submit();
					}
		       </script>
		   <?php
	}
	
	public function processOrder($data){
		$response	= $this->initSalesRequest($data,'process-order');
		return $response;
	}
	
	public function getSalesReport($data){
		$response	= $this->initRequest($data,'get-sales-report');
		return $response;
	}
	
	public function getSalesOrder($data){
	
		$response	= $this->initRequest($data,'get-sales-order');
		return $response;
	}
	
	//For Get Authorize code
	public function getAuthorizeCode(){
		$goToUrl	= $this->tokenUrl.'authorize?response_type=code&client_id='.$this->clientId.'&state=event';
		header('Location: '.$goToUrl);
	}
	
	//For get Token from Authorize code
	public function getToken($code){
		//echo $this->clientSecret; die;
		//echo "Tehre"; die;
		$request_string					= 'client_id='.$this->clientId.'&client_secret='.$this->clientSecret.'&grant_type=authorization_code&code='.$code;
		$url 							= $this->tokenUrl.'token';
		$ch 							= curl_init($url) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS,$request_string);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response 						= curl_exec($ch);
		return $response;
	}
	
	private function initRequest($data,$requested_call){
		$request_string					= '';
		if(!empty($data)) {
			foreach($data as $key=>$value) { 
				$request_string .= '&'.$key.'='.$value; 
			}
		}
		$url 							= $this->apiUrl.$requested_call;
		$ch 							= curl_init($url) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS,$request_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization : Bearer '.$this->accessToken));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response 						= curl_exec($ch);
		//echo "<br><pre>"; print_r($response); die;
		$response 						= str_replace('(','',$response);
		$response						= str_replace(')','',$response);
		$response_data					= json_decode(urldecode($response),true);
		return $response_data;
	}
	
	private function initSalesRequest($data,$requested_call){
		
		$totalQuantity	= 0;
		foreach($data['tickets'] as $ticket){
			$totalQuantity += $ticket['quantity'];
		}
		$hash_string					= $this->secretSoap."|".$data['eventId']."|".$data['billingName'].'|'.$data['billingEmailId'].'|'.$data['billingPhoneNo'].'|'.$totalQuantity.'|'.$this->accessToken;
		$hash 							= strtolower(hash('sha512', $hash_string));
		$data['hash']					= $hash;
		$url 							= $this->apiUrl.$requested_call;
		$data2 							= http_build_query($data);
		$ch 							= curl_init($url) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS,$data2);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization : Bearer '.$this->accessToken));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response 						= curl_exec($ch);
		//echo "<br><pre>"; print_r($response); die;
		$response 						= str_replace('(','',$response);
		$response						= str_replace(')','',$response);
		$response_data					= json_decode(urldecode($response),true);
		return $response_data;
	}
	
}

final class Event {
	function __construct() {
		$this->type					= 'ticketing';
		$this->eventType 			= 'public';
		$this->eventTitle			= NULL;
		$this->subdomain			= NULL;
		$this->url					= NULL;
		$this->category				= 'technology';
		$this->startDate			= NULL;
		$this->endDate				= NULL;
		$this->startTime			= NULL;
		$this->endTime				= NULL;
		$this->aboutEvent			= NULL;
		$this->listingImage			= NULL;
		$this->headerImage 			= NULL;
		$this->contactInfo 			= NULL;
		$this->country 				= NULL;
		$this->state 				= NULL;
		$this->city 				= NULL;
		$this->postal  				= NULL;
		$this->address 				= NULL;
		$this->eventId				= NULL;
		$this->ticketId				= NULL;
	}
	
	function validateEvent(){
		if($this->type == NULL)
			return false;
		if($this->eventType == NULL)	
			return false;
		if($this->eventTitle == NULL)
			return false;
		if($this->url == NULL)
			return false;
		if($this->category == NULL)
			return false;
		if($this->startDate == NULL)
			return false;
		if($this->endDate == NULL)
			return false;
		if($this->startTime == NULL)
			return false;
		if($this->endTime == NULL)
			return false;
		if($this->aboutEvent == NULL)
			return false;
		if($this->contactInfo == NULL)
			return false;
		if(strlen($this->aboutEvent) > 2000)
			return false;
		
		return true;

	}
}


final class Ticket {
	function __construct() {
		$this->eventId				= NULL;
		$this->ticketName 			= NULL;
		$this->ticketDescription  	= NULL;
		$this->price 				= NULL;
		$this->currency 			= 'INR';
		$this->quantity 			= 50;
		$this->status 				= 'published';
		$this->startDate 			= NULL;
		$this->endDate				= NULL;
		$this->startTime			= NULL;
		$this->endTime				= NULL;
		$this->minQuantity 			= 1;
		$this->maxQuantity 			= 10;
		$this->serviceFee  			= 1;
		$this->allowCancellation 	= false;
	}

	function validateTicket(){
		if($this->eventId == NULL)
			return false;
		if($this->ticketName == NULL)
			return false;
		if($this->currency == NULL)
			return false;
		if($this->quantity == NULL)
			return false;
		if($this->status == NULL)
			return false;
		if($this->serviceFee == NULL)
			return false;

		return true;

	}
}

final class Discount {
	function __construct() {
		$this->eventId				= NULL;
		$this->ticketId				= NULL;
		$this->discountId			= NULL;
		$this->discountCategory  	= 'flat';
		$this->discount   			= NULL;
		$this->discountType  		= 'fixed';
		$this->limit  				= NULL;
		$this->fromQuantity  		= NULL;
		$this->toQuantity  			= NULL;
		$this->code  				= NULL;
		$this->startDate 			= NULL;
		$this->endDate				= NULL;
		$this->startTime			= NULL;
		$this->endTime				= NULL;
	}

	function validateDiscount(){
		if($this->eventId == NULL)
			return false;
		if($this->ticketId == NULL)
			return false;
		if($this->discountCategory == NULL)
			return false;
		if($this->discount == NULL)
			return false;
		if($this->discountType == NULL)
			return false;
		if($this->fromQuantity == NULL)
			return false;
		if($this->toQuantity == NULL)
			return false;
		if($this->code == NULL)
			return false;

		return true;

	}
}

final class Booking {
	function __construct() {
		$this->amount			= NULL;
		$this->orderNo			= NULL;
		$this->name   			= NULL;
		$this->emailId  		= NULL;
		$this->phoneNo    		= NULL;
		$this->country  		= NULL;
		$this->state    		= NULL;
		$this->city				= NULL;
		$this->address  		= NULL;
		$this->zipcode  		= NULL;
		$this->callbackUrl      = NULL;
		$this->currency 		= 'INR';
		$this->pg    			= 'pg2';
	}

	function validateDiscount(){
		if($this->amount == NULL)
			return false;
		if($this->orderNo == NULL)
			return false;
		if($this->name == NULL)
			return false;
		if($this->emailId == NULL)
			return false;
		if($this->phoneNo == NULL)
			return false;
		if($this->country == NULL)
			return false;
		if($this->state == NULL)
			return false;
		if($this->city == NULL)
			return false;
		if($this->address == NULL)
			return false;
		if($this->zipcode == NULL)
			return false;
		if($this->returnUrl == NULL)
			return false;
		if($this->currency == NULL)
			return false;
		if($this->pg == NULL)
			return false;
		return true;

	}
}


//S001 : Event is created successfully
//S002 : Event is updated successfully
//S003 : Ticket created successfully
//S004 : Discount created successfully
//E001 : Invalid API Key or Private Key
//E002 : All mandatory fields are required
//E003 : End date time should be greater than Start date time
//E004 : Start date should be greater than or equal to today's date
//E005 : Subdomain already used by other user. Try other subdomain name
//E006 : Url is already used in other event. Try other url
//E007 : Invalid Event Id
//E008 : Invalid API Key for given Event Id
//E009 : Url is already used by other event. Try other url
//E010 : Invalid Location Id
//E011 : Some error occured. Pleae try later
//E012 : Invalid Ticket Id
//E013 : Only numeric character allowed in quantity
//E014 : Only numeric character allowed in price
//E015 : Quantity is required
//E016 : Ticket Name is required
//E017 : Start time format is incorrect
//E018 : End time format is incorrect.
//E019 : Invalid Discount Id
//E020 : Invalid Publisher Key
//E021 : Category is required
//E022 : Response Private Key is not matching 
//E023 : Is there a bot? We are getting too many request from you!
//T001 : Invalid request. Request Ticket is missing
//T002 : User Billing information is missing
//T003 : Invalid hash
//T004 : No quantity selected
//T005 : System is busy. Please try later
//T006 : Please try after some time
//T007 : Order not exist
//T008 : OrderNo is not valid for given publisher key
//T009 : Date Range should be less then or equal to 31
//T010 : Order status is success. Duplicate order no

?>
