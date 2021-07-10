<?php

function start_server($user, $problem) {
	$_SESSION["status.".$user.".".$problem] = "In Queue";
	grade_submission($user, $problem);
}
?>