<?php
//ini_set('display_errors', '1');
//echo 'Time Limit = ' . ini_get('max_execution_time') .
$result = array();

function grade_submission($user, $problem) {
	$response = array();
	$response["verdict"] = "Judging...";
	
	$file_dir = "submissions/".$user."/".$problem."/solution";

	$_SESSION["status.".$user.".".$problem] = "Compiling";

	$file_ext = $file_dir . ".java";
	$file_class = $file_dir . ".class";
	
	//Try to compile
	$compilation_result = exec('compilers/jdk-11.0.11/bin/javac '.$file_ext . "", $comp_output, $comp_response);
	
	if($comp_response != "0") {
		return_verdict("Compilation Error", "0", "0", "0", "64", $user, $problem);
	}
	
	$_SESSION["status.".$user.".".$problem] = "Running Tests";
	//Compilation creates solution.class file, which creates ClasssnNtFoundError on execution below, so we delete the file
	if (file_exists($file_class)) {
		unlink($file_class);
		$fullPath = "submissions/".$user."/".$problem."/";
		array_map('unlink', glob($fullPath."*.class"));
	}
	else {
		return_verdict("Denial of Judgement", "0", "N/A", "0", "64", $user, $problem);
	}
	
	//get all input files
	$io_dir = "test-data/".$problem."/";
	$inputs = array_values(array_diff(scandir($io_dir."input/"), array('.', '..')));
	$outputs = array_values(array_diff(scandir($io_dir."output/"), array('.', '..')));
	
	if(count($inputs) != count($outputs)) {
		return_verdict("Input Preparation Failed", "0", count($inputs), "0", "64", $user, $problem);
	}
	
	$time_usage = 0;
	
	//test solution against input files
	for($index = 0; $index < count($inputs); $index++) {
		$in = $inputs[$index];
		$out = $outputs[$index];

		$test_number = (int) filter_var($in, FILTER_SANITIZE_NUMBER_INT);
		$_SESSION["status.".$user.".".$problem] = "Running Test " . $test_number;
		$input_dir =  $io_dir . "input/";
		$input_file = $input_dir . $in;
	
		$time_measure = round(microtime(true) * 1000);
		
		$status = exec('compilers/jdk-11.0.11/bin/java ' . $file_ext . " < " . $input_file, $output, $returncode);
	
	
		$cur_time = round((round(microtime(true) * 1000) - $time_measure) / 10);
		$time_usage = max($cur_time, $time_usage);
		//Debug Run: $status =  exec('compilers/jdk-11.0.11/bin/java '.$file_dir.' < ' . $input_file . " 2>&1", $output, $response);
	
		if($time_usage > (2) * 1000) {
			return_verdict("Time Limit Exceeded", $time_usage, count($inputs), $index + 1, "64", $user, $problem);
		}
	
		if($returncode == "1") {
			return_verdict("Runtime Error", $time_usage, count($inputs), $index + 1, "64", $user, $problem);
		} else if($returncode != "0") { //This shouldnt happen
			return_verdict("Judgement Failed", $time_usage, count($inputs), $index + 1, "64", $user, $problem);
		} else { //output created correctly
			if(judge($output, $problem, $out) != "0") {//wrong answer
				return_verdict("Wrong Answer", $time_usage, count($inputs), $index + 1, "64", $user, $problem);
			}
		}
		return_verdict("Accepted", $time_usage, count($inputs), count($inputs), "64", $user, $problem);
	}
	
}

function judge($user_output, $prob, $expected_file) {
	//clear empty lines
	
	$expected_dir = "test-data/".$prob."/output/".$expected_file;

	$expected = file($expected_dir, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	$user_data_cleaned = array();
	$expected_cleaned = array();
	foreach($user_output as $element) {
		$cleaned = trim($element);
		if(strlen($cleaned) > 0) array_push($user_data_cleaned, $cleaned);
	}
	foreach($expected as $element) {
		$cleaned = trim($element);
		if(strlen($element) > 0) array_push($expected_cleaned, $element);
	}
	if(count($user_data_cleaned) != count($expected_cleaned)) return "1";
	for($index = 0; $index < count($user_data_cleaned); $index++) {

		if($user_data_cleaned[$index] != $expected_cleaned[$index]) return "2";
	}
	return "0";
}
function return_verdict($verdict, $time, $tctotal, $tcpass, $memory, $user, $problem) {
	$response["verdict"] = $verdict;
	$response["time"] = $time;
	$response["tctotal"] = $tctotal;
	$response["tcpass"] = $tcpass;
	$response["user"] = $user;
	$response["problem"]  = $problem;
	$response["memory"] = $memory;
	
	$_SESSION["result.".$user.".".$problem] = json_encode($response);
	$_SESSION["status.".$user.".".$problem] = "Judgement Generated";

	die(json_encode($response));
	
}
// function setResponse($label, $response, $die) { global $result; $result[$label] = $response; if($die){ finish(); }}
// function finish() { 
//     global $result;
//     print_r(json_encode($result)); 
//     die();
// }

?>