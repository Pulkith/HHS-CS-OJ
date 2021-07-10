<?php

ob_end_clean();
header("Connection: close");
ignore_user_abort(true); // just to be safe
ob_start();

session_start();
ini_set('display_errors', '1');

require 'init_judge.php';
require 'oj.php';


//$_POST["user"] = "Monkey";
//$_POST["problem"] = "000A";

create_solution_file();

function create_solution_file() {
	if(!isset($_POST["solution"])) die("-3");
	if(!isset($_POST["user"])) die("-5");
	if(!isset($_POST["problem"])) die("-6");

	$submission = $_POST["solution"];
	$user = $_POST["user"];
	$problem = $_POST["problem"];

	$_SESSION["status.".$user.".".$problem] = "Starting Server";
	
	$dir = "submissions/".$user."/".$problem."/solution.java";
	$result = file_put_contents($dir, $submission);
	if($result){
	//if(true) {
		echo('request posted');
		$size = ob_get_length();
		header("Content-Length: $size");
		ob_end_flush(); // Strange behaviour, will not work
		flush(); // Unless both are called !


		start_server($user, $problem);
		die("0");
	}
	die("-7");
}

?>