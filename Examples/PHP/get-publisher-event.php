<?php
require_once('../../Library/PHP/Explara.php');

function publisherEvents(){
	$explara					= new Explara();
	$response					= $explara->getEventListForPublisher('business,technology');
	echo "<br><pre>"; print_r($response); die;
}
