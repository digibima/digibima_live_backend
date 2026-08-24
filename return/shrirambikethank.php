<?php
$policyNumber = $_POST['PolicyNumber'] ?? "123456";
$policyURL = $_POST['PolicyURL'] ?? "abcd";
// $param1 = $_POST['Param1'] ?? "1";
// $param2 = $_POST['Param2'] ?? "2";
// $status = $_POST['Status'] ?? "0";
//$route = 'ghghg';//route('shriram.thankyou');
//echo $route; die();
header("Location: https://test.digibima.com/motor-bike-shriram/thankyou/?policynumber=" . $policyNumber . "&policyurl=" . $policyURL);

?>