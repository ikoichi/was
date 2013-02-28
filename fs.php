<?php

define( 'BASE_DIR', substr(__FILE__, 0, strrpos(__FILE__, '/')) );

$ckfile = BASE_DIR.'/cookies.txt';

if(file_exists(BASE_DIR)){
			unlink(BASE_DIR);
}
else{
			mkdir(BASE_DIR,0755);
}
touch($ckfile);

if($_GET['action'] == 'login'){
	login($ckfile);
}
else if($_GET['action'] == 'search' && isset($_GET['userWords'])){
	search($ckfile);
}
else if($_GET['action'] == 'item' && isset($_GET['id'])){
	item($ckfile, $_GET['id']);
}



function login($ckfile = ''){

	$url = "http://www.freesound.org/forum/login.php";
	
	$ch = curl_init ($url);
	curl_setopt ($ch, CURLOPT_USERAGENT, "wcs-agent/1.0");
	curl_setopt ($ch, CURLOPT_HTTPHEADER, array (
					"Content-Length: 70"
		));
	curl_setopt ($ch, CURLOPT_COOKIEJAR, $ckfile);
	curl_setopt ($ch, CURLOPT_COOKIEFILE,$ckfile);
	curl_setopt ($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ($ch, CURLOPT_POST, 1);
	curl_setopt ($ch, CURLOPT_POSTFIELDS, "username=<username>&password=<password>&login=Log in&redirect=&autologin=on");
	curl_setopt ($ch, CURLOPT_POSTFIELDSIZE, -1);
	
	$page = curl_exec ($ch);
	
	if($_GET['debug']){
		print_r("<br><strong>Request</strong><br>");
		print_r(curl_getinfo($ch,CURLINFO_HEADER_OUT));
	
		print_r("<br><strong>Response</strong><br>".$page);
	}
	
	curl_close($ch);
}

function search($ckfile = ''){
	/*echo "<pre>";
	echo "Search<br>";
	echo $ckfile."<br>";
	
	$file = file_get_contents($ckfile, FILE_USE_INCLUDE_PATH);
	
	echo $file."<br>";
	echo "</pre>";
	*/
	
	$start = 0;
	if( isset( $_GET['start'] ) && is_numeric( $_GET['start'] )){
		$start = $_GET['start'];
	}
	
	$url = "http://www.freesound.org/searchTextXML.php?search=".urlencode($_GET['userWords'])."&searchTags=1&start=".$start."&limit=10";
	$ch = curl_init ($url);
  curl_setopt ($ch, CURLOPT_USERAGENT, "wcs-agent/1.0");
  //curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
  curl_setopt($ch, CURLOPT_COOKIEFILE,$ckfile);
  curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $page = curl_exec($ch);
	
	
  if($_GET['debug']){
    print_r("<br><strong>Request</strong><br>");
    print_r(curl_getinfo($ch,CURLINFO_HEADER_OUT));

    print_r("<br><strong>Response</strong><br>".$page);
  }

  if(strpos($page, "Login") === FALSE){
  	header("Content-type: application/xml");
		echo $page;
  }
  else{
		login($ckfile);
		search($ckfile);
  }
  curl_close($ch);
  

}

function item($ckfile, $id){
	
	$url = "http://www.freesound.org/samplesViewSingleXML.php?id=".$id;
	$ch = curl_init ($url);
  curl_setopt ($ch, CURLOPT_USERAGENT, "wcs-agent/1.0");
  //curl_setopt($ch, CURLOPT_COOKIEJAR, $ckfile);
  curl_setopt($ch, CURLOPT_COOKIEFILE,$ckfile);
  curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, 5000);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $page = curl_exec($ch);
	
	
  if($_GET['debug']){
    print_r("<br><strong>Request</strong><br>");
    print_r(curl_getinfo($ch,CURLINFO_HEADER_OUT));

    print_r("<br><strong>Response</strong><br>".$page);
  }

  if(strpos($page, "Login") === FALSE){
  	header("Content-type: application/xml");
		echo $page;
  }
  else{
		login($ckfile);
		search($ckfile);
  }
  curl_close($ch);
}

?>
