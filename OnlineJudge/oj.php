<?php
 ini_set('display_errors', '1');
//echo 'Time Limit = ' . ini_get('max_execution_time') .

$response = array();
$response["verdict"] = "Judging...";

$problem = "000A";
$user = "Monkey";

$file_dir = "submissions/".$user."/".$problem."/solution";
$file_ext = $file_dir . ".java";
$file_class = $file_dir . ".class";

//Try to compile
$compilation_result = exec('compilers/jdk-11.0.11/bin/javac '.$file_ext . "", $comp_output, $comp_response);

if($comp_response != "0") {
	$response["verdict"] = "Compilation Error";
	die(json_encode($response));
}

//Compilation creates solution.class file, which creates ClasssnNtFoundError on execution below, so we delete the file
if (file_exists($file_class)) unlink($file_class);
else {
	$response["verdict"] = "Denial of Judgement";
	die(json_encode($response));
}

//get all input files
$io_dir = "test-data/".$problem."/";
$inputs = array_values(array_diff(scandir($io_dir."input/"), array('.', '..')));
$outputs = array_values(array_diff(scandir($io_dir."output/"), array('.', '..')));

if(count($inputs) != count($outputs)) {
	$response["verdict"] = "Corrupted Testcases";
	die(json_encode($response));
}

$time_usage = 0;

//test solution against input files
for($index = 0; $index < count($inputs); $index++) {

	$in = $inputs[$index];
	$out = $outputs[$index];

	$input_dir =  $io_dir . "input/";
	$input_file = $input_dir . $in;

	$time_measure = round(microtime(true) * 1000);

	$status = exec('compilers/jdk-11.0.11/bin/java ' . $file_ext . " < " . $input_file, $output, $returncode);


	$cur_time = (round(microtime(true) * 1000) - $time_measure );
	$time_usage = max($cur_time, $time_usage);
	//Debug Run: $status =  exec('compilers/jdk-11.0.11/bin/java '.$file_dir.' < ' . $input_file . " 2>&1", $output, $response);

	if($time_usage > (3) * 1000) {
		$response["verdict"] = "Time Limit Exceeded";
		die(json_encode($response));
	}

	if($returncode == "1") {
		$response["verdict"] = "Runtime Error";
		die(json_encode($response));
	} else if($returncode != "0") { //This shouldnt happen
		$response["verdict"] = "Internal Error";
		die(json_encode($response));
	} else { //output created correctly
		if(judge($output, $problem, $out) != "0") {//wrong answer 
			$response["verdict"] = "Wrong Answer";
			die(json_encode($response));
		}
	}

	$response["verdict"] = "Accepted";
	$response["time"] = $time_usage;
	die(json_encode($response));
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


?>