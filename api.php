<?php

//$api_url = "http://10.80.5.213:8080/api/luca/";
$api_url = "http://192.168.1.100:8080/api/";

if(isset($_POST['id']) && ($_POST['action'] == 'update' || $_POST['action'] == 'get')){
	$api_url = $api_url."".$_POST['id']."/";
	//echo $api_url;
}
if(isset($_POST['action'])){
	$action = $_POST['action'];
}

$data = "project=".$_POST['data'];

// Open the Curl session
$session = curl_init($api_url);

if($action == 'update'){
	curl_setopt($session, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($session, CURLOPT_POSTFIELDS, $data);
}
else if($action == 'save'){
	curl_setopt ($session, CURLOPT_POST, true);
	curl_setopt ($session, CURLOPT_POSTFIELDS, $data);
}

curl_setopt($session, CURLOPT_HEADER, false);
curl_setopt($session, CURLOPT_TIMEOUT, 5000);
//curl_setopt($session, CURLOPT_HTTPPROXYTUNNEL, 1);

curl_setopt($session, CURLOPT_RETURNTRANSFER, false);
//curl_setopt($session, CURLOPT_FOLLOWLOCATION, true);
//curl_setopt($session, CURLOPT_MAXREDIRS, 10); /* Max redirection to follow */

// Make the call
$output = curl_exec($session);

// The web service returns XML. Set the Content-Type appropriately
//header("Content-Type: text/xml");


curl_close($session);

?>