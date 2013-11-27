<?php
require_once('smswall.inc.php');
include('func.php');
include('libs/ForceUTF8/Encoding.php');

date_default_timezone_set('Europe/Paris');

// Affichage par défaut des 30 derniers messages stockés en bdd
$offset = (isset($_POST['offset'])) ? $_POST['offset'] : 0;
$limit = (isset($_POST['limit'])) ? $_POST['limit'] : 30;

$result = $db->query("SELECT * FROM messages ORDER BY ctime DESC LIMIT ".$offset.",".$limit);
$rowarray = $result->fetchall(PDO::FETCH_ASSOC);

$response = array();
foreach($rowarray as $row){
	$msg = array();
	foreach($row as $key => $value) {
		if($key == "ctime"){
			$timestamp = strtotime($value) + date("Z");
			$value = date("Y-m-d H:i:s", $timestamp);
		}
		// définition des avatars par défaut en fonction du provider (SMS, WWW)
		if(($key == "avatar" && !$value) && $row['provider'] != 'TWITTER'){
			$value = 'default_'.strtolower($row['provider']).'.png';
		}

		if($key == "message" || $key == "message_html"){
			$value = \ForceUTF8\Encoding::toUTF8($value);
		}

		$msg[$key] = $value;
	}
	$response[] = $msg;
}

header('Content-type: application/json');
echo json_encode($response);
?>