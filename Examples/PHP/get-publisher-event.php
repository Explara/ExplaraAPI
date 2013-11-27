<?php

function publisherEventsAction(){
	require_once('../Explara.php');
	$explara					= new Explara();
	$response					= $explara->getEventListForPublisher('business,technology');
	echo "<br><pre>"; print_r($response); die;
}
