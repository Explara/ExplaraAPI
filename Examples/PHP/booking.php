<?php
require_once('../../Library/PHP/Explara.php');

function bookingAction(){
	$explara					= new Explara();
	$booking					= new Booking();
	$booking->amount			= 200;
	$booking->orderNo			= uniqid('ORDER');
	$booking->name   			= 'Pankaj Kumar';
	$booking->emailId  			= 'xxxxx@xxxxxxxxx.com';
	$booking->phoneNo    		= '66112233';
	$booking->country  			= 'India';
	$booking->state    			= 'Karnataka';
	$booking->city				= 'Bangalore';
	$booking->address  			= 'MG Raod';
	$booking->zipcode  			= '560001';
	$booking->returnUrl         = 'http://mydomain.com/booking-response';
	$booking->currency 			= 'INR';
	$booking->pg    			= 'pg2';
	$response					= $explara->doBooking($booking);
	echo "<br><pre>"; print_r($response); die;
}

function bookingResponse(){
	$explara					= new Explara();
	$response					= $explara->getResponse($_POST['response']);				
	echo "<br><pre>"; print_r($response); die;
}