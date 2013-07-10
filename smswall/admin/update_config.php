<?php

require_once('../smswall.inc.php');
//include('init_db.php');

// Lib Pusher ( websockets )
require('../libs/Pusher.php');

$up_hashtag = (!empty($_POST['hashtag'])) ? $_POST['hashtag'] : "";
$up_userstream = (!empty($_POST['userstream'])) ? $_POST['userstream'] : "";
$up_modo_type = (!empty($_POST['modo_type'])) ? $_POST['modo_type'] : "";
$up_avatar = (!empty($_POST['avatar'])) ? $_POST['avatar'] : "";
$up_theme = (!empty($_POST['theme'])) ? $_POST['theme'] : "";
$up_channel = (!empty($_POST['channel'])) ? $_POST['channel'] : "";
$up_retweet = (!empty($_POST['retweet'])) ? $_POST['retweet'] : "";
$up_phone = (!empty($_POST['phone'])) ? $_POST['phone'] : "";

// Choix du type de stream
if(!empty($up_userstream)){
	try {
		$toggleUserTag = ($up_userstream == 'user') ? '1' : '0';
		$sql = "UPDATE config_wall SET userstream = ? WHERE id = 1;";
		$q = $db->prepare($sql);
		$q->execute(array($toggleUserTag));

		$response['userstream'] = $up_userstream;
	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Update de la chaine hashtag
if(!empty($up_hashtag)){
	try {
		$sql = "UPDATE config_wall SET hashtag = ? WHERE channel_id = ?;";
		$q = $db->prepare($sql);
		$q->execute(array( utf8_decode($up_hashtag), $up_channel ));

		$arrayPush['hashtag'] = $up_hashtag;
    	$pusher = PusherInstance::get_pusher();
    	$pusher->trigger('Channel_' . $up_channel, 'update_hashtag', $arrayPush);

		$response['hashtag'] = utf8_encode($up_hashtag);

	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Changement de modération
if(!empty($up_modo_type)){
	try {
		$toggleModo = ($up_modo_type == 'pos') ? '1' : '0';
		$sql = "UPDATE config_wall SET modo_type = ? WHERE id = 1;";
		$q = $db->prepare($sql);
		$q->execute(array($toggleModo));

		$response['modo_type'] = $up_modo_type;
	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Changement de thème
if(!empty($up_theme)){
	try {
		$sql = "UPDATE config_wall SET theme = ? WHERE id = 1;";
		$q = $db->prepare($sql);
		$q->execute(array($up_theme));

		$arrayPush['newtheme'] = $up_theme;
		$pusher = PusherInstance::get_pusher();
		$pusher->trigger('Channel_' . $up_channel, 'update_theme', $arrayPush);

		$response['theme'] = $up_theme;

	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Affichage des avatars
if(!empty($up_avatar)){
	try {
		$toggleAvatar = ($up_avatar == 'show') ? '1' : '0';
		$sql = "UPDATE config_wall SET avatar = ? WHERE channel_id = ?;";
		$q = $db->prepare($sql);
		$q->execute(array($toggleAvatar,$up_channel));

		$arrayPush['avatar'] = 'refresh';
    	$pusher = PusherInstance::get_pusher();
    	$pusher->trigger('Channel_' . $up_channel, 'update_avatar', $arrayPush);

		$response['avatar'] = $up_avatar;

	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Afficher / Masquer les RTs
if(!empty($up_retweet)){
	try {
		$toggleRetweet = ($up_retweet == 'show') ? '1' : '0';
		$sql = "UPDATE config_wall SET retweet = ? WHERE channel_id = ?;";
		$q = $db->prepare($sql);
		$q->execute(array($toggleRetweet,$up_channel));

		$response['retweet'] = $up_retweet;

	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

// Update du n° phone
if(!empty($up_phone)){
	try {
		$sql = "UPDATE config_wall SET phone_number = ? WHERE channel_id = ?;";
		$q = $db->prepare($sql);
		$q->execute(array( utf8_decode($up_phone), $up_channel ));

		$arrayPush['phone'] = $up_phone;
    	$pusher = PusherInstance::get_pusher();
    	$pusher->trigger('Channel_' . $up_channel, 'update_phone', $arrayPush);

		$response['phone'] = utf8_encode($up_phone);

	} catch(PDOException $e) {
		$response['error'] = $e->errorInfo();
	}
}

echo json_encode($response);

?>
