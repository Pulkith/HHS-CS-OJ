
<?php
session_start();

// $user = $_POST["user"];
// $problem = $_POST["problem"];
if(!isset($_POST["user"]) || !isset($_POST["problem"])) die("-2");

$user = $_POST["user"];
$problem = $_POST["problem"];

$str = "result.".$user.".".$problem;
$res = array();

if(!isset($_SESSION[$str])) {
	$res["response"] = "-3";
} else {
	$res["response"] = "0";
	$res["details"] = $_SESSION[$str];
}

die(json_encode($res));
?>