<?php
require_once('../smswall.inc.php');
header('Content-Type: application/json');
$response = array();
$ari_p = $_GET['param'];
foreach ($ari_p as $p) {
    $response[$p] = $config[$p];
}
echo json_encode($response);
?>