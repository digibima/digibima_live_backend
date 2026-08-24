<?php
echo "ultimate";
$policyNumber = $_POST['policyNumber'] ?? null;
//echo getconstant('SITE_URL');
//print_r(getconstant('CARESUPREME.ADDON')); die;
header("Location: https://test.digibima.com/health-caresupereme/thankyou/".$policyNumber);
?>