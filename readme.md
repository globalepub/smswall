Présentation
============

__Affichage temps réel de contributions envoyées par SMS et/ou Twitter sous forme d'un 'wall' personnalisable et modérable__


##Infos

Ce dépôt contient 3 applications différentes conçues pour fonctionner ensemble. Elles sont malgré tout indépendantes et communiquent entre elles grace à Pusher. Elles peuvent donc être facilement adaptées ou remplacées.


###Le SmsWall
__/smswall__

C'est la partie WEB du projet. Le SmsWall vous permet d'une part d'afficher un mur contributif qui agrège les tweets et les SMS envoyés par les utilisateurs en temps réel. D'autre part, il permet à l'animateur de "piloter" le wall: mettre en valeur certains tweets ou leur contenu (photo, vidéo, ...) en les affichant dans une bulle; afficher ou masquer certains tweets; envoyer des messages; ...

###Le Grabber
__/grabber__

Cette petite application en Python branchée sur l'API Streaming de Twitter est en charge de récupérer tous les tweets correspondant à votre recherche (hashtags) en temps réel. Le grabber se lance en ligne de commande dans un terminal. Il ouvre une connexion authentifiée avec l'API de Twitter et doit rester connecter en tâche de fond.

###Le Grabber de SMS
__/tasker__

Un simple téléphone mobile sous Android suffit désormais pour alimenter le wall !

Ce script utilise l'application Android Tasker pour afficher sur le wall tous les SMS qui sont envoyés au mobile sur lequel tourne Tasker.

Cette solution est plus simple à mettre en place que la solution clé 3G + SmsEnabler. Malgré cela, cette solution n'est pas imposée, l'utilisation de SmsEnabler reste compatible avec la version actuelle du SmsWall.


---



__SMS :__

Numéro d'envoi non surtaxé pour les participants

Materiel, 2 solutions proposées :

+   Clé 3G + n'importe quelle carte SIM capable de recevoir des SMS + SMS Enabler
+   Téléphone mobile Android + application Tasker

__Twitter :__

-   Filtrage des tweets sur un ou plusieurs #hashtag
-   ou affichage affichage de la timeline du compte Twitter utilisé pour l'authentification (https://dev.twitter.com/docs/streaming-apis/streams/user)
-   Pilotage et animation du wall depuis l'admin
-   Modération des tweets et SMS à priori ou à posteriori.
-   Masquage des RT
-   Possibilité de créer ses propres thèmes


__Medias intégrés :__

Embedly - http://embed.ly

Les liens intégrés dans les tweets peuvent être décompressés et analysé. Dans le cas de photos, vidéos, pdf le média peut être affiché dans une bulle à la demande. Le modérateur "pilote" l'affichage depuis la console d'administration du wall

Les liens externes, non interpretés par l'API Twitter sont envoyés à http://embed.ly/ qui les analyses et retourne un 'embed' en fonction du type de ressources. La création d'un compte embedly est donc requise (l'inscription est gratuite et vous pourrez afficher jusqu'à 5000 embeds / mois)


__Websocket :__

Pusher - http://pusher.com

La communication entre les divers éléments qui composent le SmsWall se fait en temps réel grace au service en ligne Pusher.com. Pour une utilisation basique un compte gratuit suffit amplement (Vous pourrez publier 100 000 messages par jour...)


Amusez-vous bien :)


Licence ???

