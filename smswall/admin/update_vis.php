<?php

require_once('../smswall.inc.php');
require('../libs/Pusher.php');

$oldvis = $_POST['oldvis'];
$id = $_POST['id'];

$response = array();

if(isset($oldvis) && isset($id)){
	$visible = ($oldvis == '1') ? '0' : '1';

	$db->beginTransaction();
	$q = $db->prepare('UPDATE messages SET visible = ? WHERE id = ?;');
	$q->execute(array($visible,$id));
	$db->commit();

	$response['id'] = $id;
	$response['visible'] = $visible;

	$pusher = PusherInstance::get_pusher();
	if($visible){
		$pusher->trigger('Channel_' . $config['channel_id'], 'show_twut', $response);
	}else{
		$pusher->trigger('Channel_' . $config['channel_id'], 'hide_twut', $response);
	}

}else{
	$response["error"] = "Paramètres manquants";
}

echo json_encode($response);
