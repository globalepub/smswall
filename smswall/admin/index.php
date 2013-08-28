<?php
require_once('../smswall.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>TwitWall Factory</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="../static/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link rel=stylesheet type=text/css href="static/main.css">
	<script src="http://js.pusher.com/1.12/pusher.min.js" type="text/javascript"></script>
	<script type="text/javascript">
		var config = <?php echo json_encode($config); ?>;
		config.PUSHER_KEY = '<?php echo PUSHER_KEY; ?>';
		config.EMBEDLY_KEY = '<?php echo EMBEDLY_KEY; ?>';
	</script>
</head>

<body>
<div id="menu">
	<div class="left">
		<div id="countscr" data-toggle="tooltip" data-placement="bottom" title="Nombre de wall ouvert en ce moment"><span id="countmbr"></span></div>
		<?php
		if($config['userstream']){
			$tagDisplay = "display: none;";
			$userDisplay = "";
		}else{
			$tagDisplay = "";
			$userDisplay = "display: none;";
		}
		$configHash = utf8_encode($config['hashtag']);
		$iscroped = (strlen($config['hashtag']) > 20) ? "..." : "";
		?>
		<div id="hashtag_btn" title="<?php echo $configHash; ?>" class="btn btn-inverse" data-toggle="tooltip" data-placement="bottom" style="<?php echo $tagDisplay; ?>">
			<i class="icon-search icon-white"></i> <?php echo substr($configHash, 0, 20).$iscroped; ?>
		</div>

	    <div id="hashtag_input" class="input-append hide">
	    	<form id="hashFormMenu" class="form-inline">
			    <input id="hashMenuInput" class="span3" type="text" value="<?php echo $configHash; ?>">
			    <button class="btn" type="submit">Enregistrer</button>
			    <button class="btn cancel" type="button">X</button>
			</form>
	    </div>


		<div id="userstream_choice" title="Stream live du compte de l'application" class="btn btn-inverse" data-toggle="tooltip" data-placement="bottom" style="<?php echo $userDisplay; ?>">
			<i class="icon-user icon-white"></i> Userstream
		</div>

		<div id="fresher" class="btn btn-warning" style="display: none;">
			<img src="../media/spinner_light.gif" width="16" height="16" />
		</div>
		<a id="closerSplash" href="javascript://" class="btn btn-warning" style="display: none;"><i class="icon-exclamation-sign"></i> Fermer la bulle</a>
	</div>
	<div class="right">
		<div id="options" onclick="affichOptions();" data-toggle="tooltip" data-placement="bottom" title="Affichez les options"><i class="icon-wrench icon-white"></i></div>
		<div id="btnmsgmodo" onclick="affichMessage();" data-toggle="tooltip" data-placement="bottom" title="Postez un message"><i class="icon-comment icon-white"></i></div>

	</div>
	<div id="stacker" class="btn btn-danger"></div>
</div>

<div id="overlay" style="display: none;">
	<div id="bulleViewer" class="bulle">
		<div id="bulleContent">
			<div id="closerViewer"><img src="../media/closer.png" width="48" height="48" /></div>
			<div class="title">Affichage d'une bulle <small>Prévisualisation du contenu d'un lien</small></div>
			<div class="contentembed"></div>
		</div>
	</div>
	<?php include('inc_modal_options.php'); ?>
</div>

<div id="messageModo" style="display: none;">
	<form id="posterModo" method="post">
		<label for="pseudoM">Pseudo : </label>
		<input id="pseudoM" type="text" class="input-block-level" name="pseudoM" value="" />
		<label for="messageM">Message : </label>
		<textarea id="messageM" rows="3" cols="50" class="input-block-level" name="messageM"></textarea>
		<input class="btn btn-block" type="submit" value="Envoyer" />
	</form>
</div>

<ul id="containerMsg"></ul>

<div id="morer" class="msgOK">Afficher plus de messages</div>

<!-- Templates -->

<script id="tpl_tweet" type="text/template">
<li class="<%= parseInt(visible) ? 'msgOK' : 'msgNO' %>" id="t<%= id %>" style="display: none;">
	<div class="modo_menu">

		<% if(typeof(links) != 'undefined' && links.length){
			links = eval(links);
			if(typeof(links) != "undefined") {
				_.each(links, function(link){ %>

					<a href="<%= link.expanded_url %>" class="mediaicon" data-id="<%= id %>">&nbsp;</a>

				<% });
			}
		}

		if(typeof(medias) != 'undefined' && medias.length) {
	    	medias = eval(medias);
	    	if(typeof(medias) != "undefined") {
		    	_.each(medias, function(media){ %>
		    		<div class="thumbnails">
		    		<a href="javascript://" class="thumbnail" onclick="directLink(<%= id %>,'<%= media.media_url %>');">
		    			<img src="<%= media.media_url %>:<%= media.thumb_size %>" style="width: 40px; height: 40px;" />
		    		</a>
		    		</div>
		    	<% });
		    }
		} %>

	    <a href="javascript://" class="bulOff">&nbsp;</a>
		<a href="javascript://" class="togoff" onclick="hide(<%= id %>);">&nbsp;</a>
	    <a href="javascript://" class="togon" onclick="show(<%= id %>);">&nbsp;</a>
	</div>
    <img src="<%= avatar %>" class="avatar" />
    <div class="twut-text">
        <span class="author"><a href="http://twitter.com/<%= author %>" target="_blank"><%= author %></a> : </span>
        <span class="textMsg"><%= message_html ? (message_html) : message %> - </span>
        <span class="time"><%= ctime %></span>
    </div>
    <div style="clear: both;"></div>
</li>
</script>

<script id="tpl_thumbnailer" type="text/template">
<div class="thumbnails">
	<a href="javascript://" class="thumbnail" onclick="directLink(<%= id %>,'<%= url %>');">
		<img src="<%= url %>" style="width: 40px; height: 40px;" />
	</a>
</div>
</script>

<script id="tpl_viewer" type="text/template">

	<div class="btn-bar">
		<div class="splasheron btn btn-success">Ouvrir dans une bulle</div>
		<div class="splasheroff btn btn-warning">Fermer la bulle</div>
	</div>
	<% if(typeof thumbnail_url != "undefined"){ %>
		<div id="imgCont" style="width: 100%;"></div>
		<% if(type == "link"){ %>
			<div class="well well-small-viewer">
			Cette image a été extraite du contenu de la page en lien et peut ne pas être en relation direct avec le tweet<br/>
			</div>
		<% } %>
    <% } %>
    <% if(type == "rich" || type == "video"){ %>
    	<%= html %>
    <% } %>
	<div id="simpleLink">
		<strong><%= type %> :</strong> <a href="<%= url %>" target="_blank"><%= url %></a>
	</div>
</script>

<script id="tpl_img_loader" type="text/template">
	<div id="imgCont" style="width: 100%;"><div id="spinnerSplash">Loading...</div></div>
</script>

<script id="tpl_thumb" type="text/template">
	<div class="thumbnails">
	<a href="javascript://" class="thumbnail" onclick="directLink(<%= id %>,'<%= media.media_url %>');">
		<img src="<%= media.media_url %>" style="width: 40px; height: 40px;" />
	</a>
	</div>
</script>

<script src="http://code.jquery.com/jquery.js" type="text/javascript"></script>
<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js" type="text/javascript"></script>
<script src="../static/js/underscore-min.js" type="text/javascript"></script>
<script src="../static/bootstrap/js/bootstrap.min.js"></script>
<script src="http://cdn.embed.ly/jquery.embedly-3.1.0.min.js" type="text/javascript"></script>
<script src="../static/js/jquery.scrollTo-min.js" type="text/javascript"></script>
<script src="static/admin.js" type="text/javascript"></script>

</body>
</html>

