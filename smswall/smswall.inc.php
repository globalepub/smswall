<?php
require('conf.inc.php');

/**
 * Mode debug, désactivé par défaut
 */
if (!defined('STW_DEBUG')) {
	define('STW_DEBUG',0);
}

if (STW_DEBUG) {
	ini_set('html_errors', 1);
    ini_set('display_startup_errors', 1);
    ini_set('display_errors', 1);
    ini_set('error_reporting', E_ALL | E_STRICT);
}

/**
 * Smswall : common code
 */
if ($_SERVER['SCRIPT_FILENAME'] === __FILE__) {
	die('Dead end');
}

/**
 * Connexion base de donnée
 */
try {
	$db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
}
catch(PDOException $e){
	echo $e->getMessage();
}

/**
 * Lecture de la configuration
 */
try {
    $qconfig = $db->query("SELECT * FROM config_wall");
    $config = $qconfig->fetch(PDO::FETCH_ASSOC);
}
catch(PDOException $e){
    echo $e->getMessage();
}
