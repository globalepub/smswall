Présentation
============

__Affichage temps réel de contributions envoyées par SMS et/ou Twitter sous forme d'un 'wall' personnalisable et modérable__


##Infos

Ce dépôt contient 3 applications différentes conçues pour fonctionner ensemble. Elles sont malgré tout indépendantes et communiquent entre elles grace à Pusher. Elles peuvent donc être facilement adaptées ou remplacées.



###Le SmsWall

C'est la partie WEB du projet. Le SmsWall vous permet d'une part d'afficher un mur contributif qui agrège les tweets et les SMS envoyés par les utilisateurs en temps réel. D'autre part, il permet à l'animateur de "piloter" le wall: mettre en valeur certains tweets ou leur contenu (photo, vidéo, ...) en les affichant dans une bulle, d'afficher ou masquer certains tweets, d'envoyer des messages "de services", ...

[Documentation](https://github.com/assobug/smswall/tree/master/smswall#smswall)



###Le Grabber

Cette petite application en Python branchée sur l'API Streaming de Twitter est en charge de récupérer tous les tweets correspondant à votre recherche (hashtags) en temps réel. Le grabber se lance en ligne de commande dans un terminal. Il ouvre une connexion authentifiée avec l'API de Twitter et doit rester connecter en tâche de fond.

[Documentation](https://github.com/assobug/smswall/tree/master/grabber#grabber)



###Le Grabber de SMS

Il est désormais possible d'utiliser un appareil sous Android (avec carte SIM) pour récupérer les SMS. Ce script utilise l'application Tasker pour afficher sur le wall tous les SMS qui sont envoyés au mobile sur lequel tourne Tasker.

Cette solution est plus simple à mettre en place que la solution clé 3G + SmsEnabler. Les deux solutions sont compatibles avec le SmsWall, à vous de choisr la plus adaptée au materiel que vous avez à votre disposition. 

[Documentation Tasker](https://github.com/assobug/smswall/tree/master/tasker#tasker) | 
[Documentation SmsEnabler](https://github.com/assobug/smswall/tree/master/tasker#smsenabler)

---

Pusher et Embedly
=================


###Websocket

__Pusher :__ <http://pusher.com>

La communication entre les divers éléments qui composent le SmsWall se fait en temps réel grace au service en ligne Pusher.com. Pour une utilisation basique un compte gratuit suffit amplement (Vous pourrez publier 100 000 messages par jour...)

...


###Links et Medias

__Embedly :__ <http://embed.ly>

Vous pouvez affichez en mode plein écran les photos ou vidéos jointes dans les messages envoyés.

Les liens externes, non interpretés par l'API Twitter sont envoyés à http://embed.ly/ qui les décompresse et retourne un 'embed' en fonction du type de plateforme par laquelle elle a été envoyée: Instagram, Youtube, Flickr, ... + de 250 plateformes de partage de contenu sont reconnues. La création d'un compte Embedly est donc requise (l'inscription est gratuite et vous pourrez afficher jusqu'à 5000 embeds / mois)

Lorsqu'un ou plusieurs liens sont présents dans un message, une icone ou une miniature apparait pour chaque lien dans la prévisualisation (admin) du tweet. Si le média correspondant provient directement de Twitter il est affiché instantanément. Par contre une simple icone sera affichée dans un premier temps si il s'agit d'un lien non pris en charge par l'API Twitter. Celui-ci est automatiquement envoyé à Embedly lorsque vous cliquez sur l'icone correspondante et en retour vous visualiserez le contenu du lien en fonction de son type : photo, vidéo, site web. Après la première visualisation du média l'icone est remplacée, pour cette session, par une miniature (l'url finale n'est actuellement pas sauvée en BDD)

Si le lien trouvé est de type 'site web' et pas un média à proprement parler, la page est explorée et une image est éventuellement retournée par Embedly. Attention, cette image ne'est pas forcément pertinente. Il peut s'agir du logo du site, d'une publicité ou même d'une image correspondant à un autre article.



__Amusez-vous bien :)__


Licence ???
CC by SA ?

