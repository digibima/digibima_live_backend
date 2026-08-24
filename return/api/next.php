<?php

$policyNumber = $_POST['PolicyNumber'] ?? "123456";
$policyURL = $_POST['PolicyURL'] ?? "abcd";
$param1 = $_POST['Param1'] ?? "1";
$param2 = $_POST['Param2'] ?? "2";
$userId = $_POST['userid'] ?? null;

$queryParams = [
    'policynumber' => base64_encode($policyNumber),
    'policyurl' => $policyURL,
    'Param1' => $param1,
    'Param2' => $param2,
    'userid' => $userId
];

echo "<pre>";
print_r($queryParams);
echo "</pre>";
die();

$queryString = http_build_query($queryParams);
header("Location: http://192.168.1.41:3000/thankyou?" . $queryString);

exit;

//header("Location: http://192.168.1.41:3000/thankyou?policynumber=" . $queryString );
// header("Location: http://192.168.1.18:3000/thankyou?policynumber=" . $policyNumber . "&policyurl=" . $policyURL );

?>