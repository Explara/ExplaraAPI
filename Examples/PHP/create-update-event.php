<?php
require_once('../../Library/PHP/Explara.php');

function createEvent(){
	$explara					= new Explara();
	$event						= new Event();
	$event->type				= 'ticketing';
	$event->eventType 			= 'public';
	$event->eventTitle			= 'My Demo Test Event';
	$event->url					= 'my-demo-url-test';
	$event->subdomain			= 'subdomaintestdemo';
	$event->category			= 'business';
	$event->startDate			= '2013-12-30';
	$event->endDate				= '2013-12-31';
	$event->startTime			= '09:00';
	$event->endTime				= '16:00';
	$event->aboutEvent			= 'This is the descrption of the event.';
	$event->listingImage		= 'http://images2.fanpop.com/image/photos/13900000/The-Rose-of-Love-roses-13967150-1024-768.jpg';
	$event->headerImage 		= 'http://images2.fanpop.com/image/photos/13900000/The-Rose-of-Love-roses-13967150-1024-768.jpg';
	$event->contactInfo 		= 'pankaj@explara.com';
	$event->country 			= 'India';
	$event->state 				= 'Karnataka';
	$event->city 				= 'Bangalore';
	$event->postal  			= '560001';
	$event->address 			= 'MG Road';
	$response					= $explara->createEvent($event);
	echo "<br><pre>"; print_r($response); die;
}
	
function updateEvent(){
	$explara					= new Explara();
	$event						= new Event();
	$event->eventId				= 'EKDJHH';
	$event->type				= 'ticketing';
	$event->eventType 			= 'public';
	$event->eventTitle			= 'My Demo Event';
	$event->url					= 'url-my-demo-4';
	$event->subdomain			= 'demosubdomain4';
	$event->category			= 'business';
	$event->startDate			= '2013-12-30';
	$event->endDate				= '2013-12-31';
	$event->startTime			= '09:00';
	$event->endTime				= '16:00';
	$event->aboutEvent			= 'This is the descrption of the event.';
	$event->listingImage		= 'http://images2.fanpop.com/image/photos/13900000/The-Rose-of-Love-roses-13967150-1024-768.jpg';
	$event->headerImage 		= 'http://images2.fanpop.com/image/photos/13900000/The-Rose-of-Love-roses-13967150-1024-768.jpg';
	$event->contactInfo 		= 'pankaj@explara.com';
	$event->country 			= 'India';
	$event->state 				= 'Karnataka';
	$event->city 				= 'Bangalore';
	$event->postal  			= '560071';
	$event->address 			= 'Domlur layout';
	$response					= $explara->updateEvent($event);
	echo "<br><pre>"; print_r($response); die;
}
?>
