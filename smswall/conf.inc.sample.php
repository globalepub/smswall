<?php
/**
 * Configuration base de donnée
 */

$host="localhost";

$root="root";
$root_password="";

$user='wally';
$pass='k4m0ul0x';
$db="smswall";

/**
 * Twitter : http://dev.twitter.com
 * Remplacez les XXX par vos propres paramètres de connexion
 */

if(!defined('PUSHER_KEY')){ define('CONSUMER_KEY', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); }
if(!defined('PUSHER_SECRET')){ define('CONSUMER_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); }
if(!defined('PUSHER_SECRET')){ define('ACCESS_TOKEN', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); }
if(!defined('PUSHER_SECRET')){ define('ACCESS_TOKEN_SECRET', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); }

/**
 * Pusher : http://pusher.com
 * Remplacez les XXX par vos propres paramètres de connexion
 */

if(!defined('PUSHER_KEY')){ define('PUSHER_KEY', 'XXXXXXXXXXXXXXXXXXXX'); }
if(!defined('PUSHER_SECRET')){ define('PUSHER_SECRET', 'XXXXXXXXXXXXXXXXXXXX'); }
if(!defined('PUSHER_APPID')){ define('PUSHER_APPID', 'XXXX'); }

/**
 * Embed.ly : http://embed.ly
 * Remplacez les XXX par vos propres paramètres de connexion
 */

if(!defined('EMBEDLY_KEY')){ define('EMBEDLY_KEY', 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'); }

?>