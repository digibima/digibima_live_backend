<?php
echo "ultimate";
$policyNumber = $_POST['policyNumber'] ?? null;
//echo getconstant('SITE_URL');
//print_r(getconstant('CARESUPREME.ADDON')); die;
//header("Location:https://insurance.digibima.com/health-caresupereme/thankyou/".$policyNumber);
header("Location: https://insurance.digibima.com/health/vendors/caresupereme/payment/thankyou/".base64_encode($policyNumber));
?>