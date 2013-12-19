<?php
require_once('../../Library/PHP/Explara.php');

function createTicket(){
	$explara					= new Explara();
	$event						= new Ticket();
	$event->eventId				= 'EKDJHH';
	$event->ticketName 			= 'Registration Pass';
	$event->ticketDescription  	= 'Your Gate Pass';
	$event->price 				= 200;
	$event->currency 			= 'INR';
	$event->quantity 			= 50;
	$event->status 				= 'published';
	$event->minQuantity 		= 1;
	$event->maxQuantity 		= 10;
	$event->serviceFee  		= 1;
	$event->allowCancellation 	= false;
	$response					= $explara->createTicket($event);
	echo "<br><pre>"; print_r($response); die;
}

function updateTicket(){
	$explara					= new Explara();
	$event						= new Ticket();
	$event->eventId				= 'EKDJHH';
	$event->ticketId			= 'TKCIGJ';
	$event->ticketName 			= 'Registration Pass updated';
	$event->ticketDescription  	= 'Your Gate Pass updated';
	$event->price 				= 200;
	$event->currency 			= 'INR';
	$event->quantity 			= 50;
	$event->status 				= 'published';
	$event->minQuantity 		= 1;
	$event->maxQuantity 		= 10;
	$event->serviceFee  		= 1;
	$event->allowCancellation 	= false;
	$response					= $explara->updateTicket($event);
	echo "<br><pre>"; print_r($response); die;
}
