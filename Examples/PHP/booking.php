<?php
function bookingAction(){
	require_once('../Explara.php');
	$explara					= new Explara();
	$booking					= new Booking();
	$booking->amount			= 200;
	$booking->orderNo			= uniqid('ORDER');
	$booking->name   			= 'Pankaj Kumar';
	$booking->emailId  			= 'pankaj.kumar@signure.com';
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
	require_once('../Explara.php');
	$explara					= new Explara();
	$response					= $explara->getResponse($_POST['response']);				
	echo "<br><pre>"; print_r($response); die;
}