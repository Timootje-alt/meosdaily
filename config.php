<?php
//Version 1.2.4
ini_set("session.hash_function","sha512");
session_start();

ini_set("max_execution_time",500);

error_reporting(0);


// Meos database
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_data = "meos";

$con = new mysqli($db_host,$db_user,$db_pass,$db_data);

// Fivem ESX database
$dd = array(
"host" => "45.8.187.109",
"user" => "u64221_xDiLjDpqcZ",
"pass" => "ip7aQAnM4U3D3S8hV01Az0su",
"data" => "s64221_herogijsontop"
);

$ddcon = new mysqli($dd['host'],$dd['user'],$dd['pass'],$dd['data']);


// Site name, title, footer and browser color
$site_name = "Daily Roleplay";
$site_title = "Daily Roleplay | MEOS Systeem";
$site_footer = "Copyright © Daily Roleplay";
$browser_color = "#004682";

# Never touch this
require "GoogleAuthenticator.php";
$ga = new PHPGangsta_GoogleAuthenticator();

if (isset($_SESSION['id'])) {
	$q = $con->query("SELECT status FROM users WHERE id = '".$_SESSION['id']."' AND status = 'active'");
	if ($q->num_rows == 0) {
		Header("Location: exit.php");
	}
}

# Never touch this

if (isset($_POST)) {
	$a = $_POST;
	if (isset($a['password'])) {
		$a['password'] = NULL;
	}
	
	if (isset($a['password1'])) {
		$a['password1'] = NULL;
	}
	
	if (isset($a['password2'])) {
		$a['password2'] = NULL;
	}
}

# Never touch this
//Log
$data = 
array(
"SERVER"=>$_SERVER,
"SESSION"=>$_SESSION,
"POST"=>$a,
"GET"=>$_GET
);
$con->query("INSERT INTO pagevisitlog (
uri,
ip,
_SERVER,
_SESSION,
_POST,
_GET) 
VALUES
(
'".$_SERVER['REQUEST_URI']."',
'".$_SERVER['REMOTE_ADDR']."',
'".json_encode($data['SERVER'])."',
'".json_encode($data['SESSION'])."',
'".json_encode($data['POST'])."',
'".json_encode($data['GET'])."'
)");
$curl = curl_init();

curl_setopt($curl,CURLOPT_URL,"indexveh.php");
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_HEADER, false); 
curl_setopt($curl,CURLOPT_HEADER, false); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

if (isset($_GET)) {
	foreach($_GET as $key => $value) {
		//$_GET[$key] = $con->real_escape_string($value);
	}
reset($_GET);
}

if (isset($_POST)) {
	foreach($_POST as $key => $value) {
		//$_POST[$key] = $con->real_escape_string($value);
	}
reset($_GET);
}

if ($con->connect_error) {
    die("Meos DB connection failed: " . $con->connect_error);
}
if ($ddcon->connect_error) {
    die("ESX DB connection failed: " . $ddcon->connect_error);
}

?>
