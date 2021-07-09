<?php
$status = exec('compilers/jdk-11.0.11/bin/java -XX:+PrintFlagsFinal -version | grep HeapSize', $output, $response);

echo $status;
echo $response;
print_r($output);


?>