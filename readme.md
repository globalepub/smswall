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



__Amusez-vous bien :)__


Licence ???
CC by SA ?

