<?php
require_once('smswall.inc.php');

// Lecture de la config
try {
	$qconfig = $db->query("SELECT * FROM config_wall");
	$config = $qconfig->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
	echo $e->getMessage();
}

// Fallback sur le thème par défaut si pas de thème sélectionné
$theme = (!empty($config)) ? $config['theme'] : 'default';

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="generator" content="Bug" />
	<title>SmsTwitterWall</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">

	<link href="themes/<?php echo $theme; ?>/main.css" media="screen" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<script type="text/javascript" src="http://js.pusher.com/1.12/pusher.min.js"></script>
	<script type="text/javascript" src="static/js/underscore-min.js" ></script>
	<script type="text/javascript" src="static/js/moment.min.js" ></script>
	<script type="text/javascript" src="static/js/moment.fr.js" ></script>
	<?php
	// Optionnel
	// lecture de scripts.js dans le dossier du thème si il existe
	// Vous pouvez y ajouter vos propres scripts pour personnaliser votre thème
	if(file_exists("themes/".$theme."/scripts.js")){
		?>
		<script src="themes/<?php echo $theme; ?>/scripts.js" type="text/javascript"></script>
		<?php
	}
	?>
	<script type="text/javascript">
	// config pour javascript
	var config = <?php echo json_encode($config); ?>;
	config.PUSHER_KEY = '<?php echo PUSHER_KEY; ?>';
	</script>
	<script type="text/javascript" src="static/js/main.js"></script>
</head>
<body>

<?php
// personnalisation de l'apparence du wall public
include('themes/'.$theme.'/body.php');

// templates des bulles
include('themes/'.$theme.'/templates.php');
?>

</body>
</html>