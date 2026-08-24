<?php
$policyNumber = $_POST['PolicyNumber'] ?? "123456";
$policyURL = $_POST['PolicyURL'] ?? "abcd";

$data = $_POST;
$queryString = http_build_query($data);
// $param1 = $_POST['Param1'] ?? "1";
// $param2 = $_POST['Param2'] ?? "2";
// $status = $_POST['Status'] ?? "0";
//$route = 'ghghg';//route('shriram.thankyou');
//echo $route; die();
header("http://192.168.1.18:3000/thankyou?policynumber=" . $queryString );
// header("Location: https://test.digibima.com/motor-car-shriram/thankyou/?policynumber=" . $queryString);

?>