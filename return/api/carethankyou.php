<?php
$policyNumber = $_POST['policyNumber'] ?? null;

// echo "<pre>";
// print_r($policyNumber);
// echo "</pre>";
// die();

//header("Location: http://192.168.1.41:3000/health-caresupereme/thankyou/".base64_encode($policyNumber));
header("Location: https://insurance.digibima.com/health/vendors/caresupereme/payment/thankyou/".base64_encode($policyNumber));



?>
