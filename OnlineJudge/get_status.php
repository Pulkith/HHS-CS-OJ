<?php
session_start();

$user = $_POST["user"];
$problem = $_POST["problem"];

$str = "status.".$user.".".$problem;
$res = array();

if(!isset($_SESSION[$str])) {
	$res["response"] = "Could Not Find Solution";
} else {
	$res["response"] = $_SESSION[$str];
}

die(json_encode($res));


?>