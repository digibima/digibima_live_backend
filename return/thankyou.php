<?php
$policyNumber = $_POST['policyNumber'] ?? null;

header("Location: https://test.digibima.com/health-caresupereme/thankyou/".base64_encode($policyNumber));
?>
