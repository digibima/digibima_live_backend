<?php
$policyNumber = $_POST['PolicyNumber'] ?? "12345678";
$policyURL = $_POST['PolicyURL'] ?? "abcd";
$param1 = $_POST['Param1'] ?? "1";
$param2 = $_POST['Param2'] ?? "2";

$queryParams = [
    'policynumber' => base64_encode($policyNumber),
    'policyurl' => $policyURL,
    'Param1' => $param1,
    'Param2' => $param2,
   
];
$queryString = http_build_query($queryParams);
// header("Location: http://192.168.1.41:3000/thankyou?" . $policyNumber . "&policyurl=" . $policyURL);
//header("Location: http://192.168.1.41:3000/motor/car/vendor/shriram/payment/thankyou?" .$queryString  );

header("Location:https://insurance.digibima.com/motor/car/vendor/shriram/payment/thankyou?" .$queryString  );

?>