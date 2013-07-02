<?php
require_once('smswall.inc.php');
require('libs/Pusher.php');

$pusher = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APPID);
$presence_data = array('name' => 'publicwall');
$uid = uniqid();
echo $pusher->presence_auth($_POST['channel_name'], $_POST['socket_id'], $uid, $presence_data);
