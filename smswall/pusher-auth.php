<?php
require_once('smswall.inc.php');
require('libs/Pusher.php');

$pusher = new Pusher(PUSHER_KEY, PUSHER_SECRET, PUSHER_APPID);
echo $pusher->socket_auth($_POST['channel_name'], $_POST['socket_id']);
