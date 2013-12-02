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
	
	private $publisherKey;
	private $accessToken;
	private $clientId;
	private $clientSecret;
	private $apiUrl;
	private $bookingUrl;
	private $tokenUrl;
	
	function __construct() {
		$this->load_keys();
	}
	
	private function load_keys(){	
		$this->bookingKey				= explaraBookingKey;
		$this->publisherKey				= explaraPublisherKey;
		$this->accessToken				= accessToken;
		$this->clientId					= clientId;
		$this->clientSecret				= clientSecret;
		$this->apiUrl					= 'http://em.explara.com/api/resource/';
		$this->tokenUrl					= 'http://account.explara.com/account/oauth/';
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
	
	//For Publisher
	public function getEventListForPublisher($category){
		$data		= array('publisherKey'=>$this->publisherKey,'category'=>$category);
		$response	= $this->initRequest($data,'event-list-publisher');
		return $response;
	}
	
	public function getAuthorizeCode(){
		$goToUrl	= $this->tokenUrl.'authorize?response_type=code&client_id='.$this->clientId.'&state=event';
		header('Location: '.$goToUrl);
	}
	
	public function getToken($code){
		$request_string					= 'client_id='.$this->clientId.'&client_secret='.$this->clientSecret.'&grant_type=authorization_code&code='.$code;
		$url 							= $this->tokenUrl.'token'	;
		$ch 							= curl_init($url) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS,$request_string);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response 						= curl_exec($ch);
		$response_data					= json_decode(urldecode($response),true);
		return $response_data;
	}
	
	private function initRequest($data,$requested_call){
		$request_string					= '';
		if(!empty($data)) {
			foreach($data as $key=>$value) { 
				$request_string .= '&'.$key.'='.$value; 
			}
		}
		$url 							= $this->apiUrl.$requested_call.'?access_token='.$this->accessToken;
		$ch 							= curl_init($url) ;
		curl_setopt($ch, CURLOPT_POSTFIELDS,$request_string);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT,30);
		curl_setopt($ch, CURLOPT_POST, 1);
		$response 						= curl_exec($ch);
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
		$this->returnUrl        = NULL;
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
?>