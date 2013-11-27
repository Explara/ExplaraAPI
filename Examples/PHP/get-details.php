<?php
function getEvent(){
	require_once('application/includes/Explara.php');
	$explara					= new Explara();
	$response					= $explara->getEvent('EKDJHH');
	echo "<br><pre>"; print_r($response); die;
}

function getEventList(){
	require_once('application/includes/Explara.php');
	$explara					= new Explara();
	$response					= $explara->getEventList();
	echo "<br><pre>"; print_r($response); die;
}

function getTickets(){
	require_once('application/includes/Explara.php');
	$explara					= new Explara();
	$response					= $explara->getTickets('EKDJHH');
	echo "<br><pre>"; print_r($response); die;
}

function getReportAction(){
	require_once('application/includes/Explara.php');
	$explara					= new Explara();
	$response					= $explara->getReport('EKCACB');
	echo "<br><pre>"; print_r($response); die;
}

function getAttendee(){
	require_once('application/includes/Explara.php');
	$explara					= new Explara();
	$response					= $explara->getAttendee('EKCACB',0,5);
	echo "<br><pre>"; print_r($response); die;
}