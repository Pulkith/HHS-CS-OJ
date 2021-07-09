<?php

if(!isset($_POST["solution"])) die("-3");
if(!isset($_POST["user"])) die("-5");
if(!isset($_POST["problem"])) die("-6");

$submission = $_POST["solution"];
$user = $_POST["user"];
$problem = $_POST["problem"];

$dir = "../../Online-Judge/submissions/".$user."/".$problem."/solution_.java";

$result = file_put_contents($dir, $submission);


if($result) die("0");
die("-7");

?>