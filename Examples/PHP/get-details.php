<?php
require_once('../../Library/PHP/Explara.php');

function getEvent(){
	$explara					= new Explara();
	$response					= $explara->getEvent('EKDJHH');
	echo "<br><pre>"; print_r($response); die;
}

function getEventList(){
	$explara					= new Explara();
	$response					= $explara->getEventList();
	echo "<br><pre>"; print_r($response); die;
}

function getTickets(){
	$explara					= new Explara();
	$response					= $explara->getTickets('EKDJHH');
	echo "<br><pre>"; print_r($response); die;
}

function getReport(){
	$explara					= new Explara();
	$response					= $explara->getReport('EKCACB');
	echo "<br><pre>"; print_r($response); die;
}

function getAttendee(){
	$explara					= new Explara();
	$response					= $explara->getAttendee('EKCACB',0,5);
	echo "<br><pre>"; print_r($response); die;
}