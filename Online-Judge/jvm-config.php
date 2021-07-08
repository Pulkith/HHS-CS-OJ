<?php
$status = exec('compilers/jdk1.8.0_291/bin/java -XX:+PrintFlagsFinal -version | grep HeapSize', $output, $response);

echo $status;
echo $response;
print_r($output);


?>