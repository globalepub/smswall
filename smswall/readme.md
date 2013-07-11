SmsWall
=======

Affichage temps réel de contributions envoyées par SMS et/ou Twitter sous forme d'un 'wall' personnalisable et modérable

Spécifications :
----------------

-   Filtrage des tweets sur un ou plusieurs #hashtag
-   OU
-	Affichage de la timeline du compte Twitter utilisé pour l'authentification (https://dev.twitter.com/docs/streaming-apis/streams/user)
-
-   Pilotage et animation du wall depuis l'admin
-   Modération des tweets et SMS à priori ou à posteriori.
-   Masquage des RT
-   Possibilité de créer ses propres thèmes


Installation du SmsWall :
-------------------------

__Pré-requis :__

- Ouvrir un compte chez [Pusher](http://pusher.com) et mettre de côté les informations de connexion à leur API. A la création de votre application Pusher pensez à cocher la case "Enable client events"

- Ouvrir un compte chez [Embedly](http://embed.ly) et mettre de côté les informations de connexion à leur API

- Posez les sources du wall (le dossier /smswall) sur votre serveur (local ou distant)

__Installation :__


- Copiez le fichier de configuration d'exemple conf.inc.sample.php et renommez le en conf.inc.php.
- Modifiez le fichier conf.inc.php avec les paramètres de connexion à votre base de données ainsi que les informations qui vous ont été fournis par Embedly et Pusher

__Création de la base de donnée__

- Créez un base de donnée que vous nommerez 'smswall' (si vous disposez des droits root sur mysql vous pouvez vous rendre sur la page http://www.mondomaine.com/smswall/admin/init_db.php)
- Pour créer les tables rendez vous sur la page http://www.mondomaine.com/smswall/admin/init_tables.php

Votre SmsWall est opérationnel, si vous utilisez le grabber de tweet vous pouvez le lancer (soit PHP, soit Python), les messages devraient commencer à tomber sur le mur.


Lancement de l'application :
----------------------------


__Administration__

Rendez vous sur http://www.mondomaine.com/smswall/admin

__Connexion à Twitter et captation des tweets__

Dans un autre onglet de votre navigateur rendez-vous à l'adresse http://www.mondomaine.com/smswall/admin/register_tweet.php

Ce script se connecte à l'API REST de Twitter et non pas à l'API streaming. Il est sans doute plus facile à mettre en place puisqu'il est en PHP mais ce n'est pas la solution la plus conseillée: Les tweets sont mis à jour périodiquement et non pas en temps réel. La documentation de Twitter est claire sur le sujet, si c'est l’exhaustivité sur un tag donné qui vous intéresse il faudra vous tourner vers l'API streaming. Vous trouverez dans le dossier [/stream](https://github.com/assobug/smswall/tree/master/stream du dépôt une petite application en Python qui vous permettra de profiter du stream facilement. Lancez-vous !

Plus d'information sur le sujet : https://dev.twitter.com/docs/api/1.1/get/search/tweets

__Affichage du wall public__

Dans un autre onglet de votre navigateur ou depuis une autre machine, rendez vous sur http://www.mondomaine.com/smswall


###Websocket

__Pusher :__ <http://pusher.com>

La communication entre les divers éléments qui composent le SmsWall se fait en temps réel grâce au service en ligne Pusher.com. Pour une utilisation basique un compte gratuit suffit amplement (Vous pourrez publier 100 000 messages par jour...)

...


###Links et Medias

__Embedly :__ <http://embed.ly>

Vous pouvez affichez en mode plein écran les photos ou vidéos jointes dans les messages envoyés.

Les liens externes, non interprétés par l'API Twitter sont envoyés à [Embedly](http://embed.ly) qui les décompresse et retourne un 'embed' en fonction du type de plate-forme par laquelle elle a été envoyée: Instagram, Youtube, Flickr, ... + de 250 plate-formes de partage de contenu sont reconnues. La création d'un compte Embedly est donc requise (l'inscription est gratuite et vous pourrez afficher jusqu'à 5000 embeds / mois)

Lorsqu'un ou plusieurs liens sont présents dans un message, une icône ou une miniature apparait pour chaque lien dans la prévisualisation (admin) du tweet. Si le média correspondant provient directement de Twitter il est affiché instantanément. Par contre une simple icône sera affichée dans un premier temps si il s'agit d'un lien non pris en charge par l'API Twitter. Celui-ci est automatiquement envoyé à Embedly lorsque vous cliquez sur l'icône correspondante et en retour vous visualiserez le contenu du lien en fonction de son type : photo, vidéo, site web. Après la première visualisation du média l'icone est remplacée, pour cette session, par une miniature (l'url finale n'est actuellement pas sauvée en BDD)

Si le lien trouvé est de type 'site web' et pas un média à proprement parler, la page est explorée et une image est éventuellement retournée par Embedly. Attention, cette image n'est pas forcément pertinente. Il peut s'agir du logo du site, d'une publicité ou même d'une image correspondant à un autre article.


###Accès protégé (.htaccess/.htpasswd) :

Un .htaccess est présent dans le dossier admin. Il faut le renommer (oter les __), lui associer un .htpasswd, et configurer le chemin d'accès du .htpasswd.

Sous *nix, pour créer le .htpasswd lancez la commande suivante :

>         htpasswd -c /path/du/.htpasswd wallmaster

Un prompt demandera le pass désiré pour le compte 'wallmaster'


