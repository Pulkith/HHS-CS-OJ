<?php

//$compilation_result = exec('compilers/jdk-16.0.1/bin/javac solution.java -g', $comp_output, $comp_response);


//print_r($comp_output);
//die();
//echo $comp_response;
//echo $compilation_result;
//$status = exec('compilers/jdk1.8.0_291/bin/java solution.java < in1.txt', $output, $response);
$status = exec('compilers/jdk1.8.0_291/bin/java -cp . solution', $output, $response);

print $response;
if($response == "1") die("Compilation or Runtime Error");

$myfile = fopen("out1.txt", "w") or die("Denial of Judgement");
foreach ($output as $item) {
	fwrite($myfile, $item);
	fwrite($myfile, "\n");
}

fclose($myfile);


?>