Présentation
============

__Affichage temps réel de contributions envoyées par SMS et/ou Twitter sous forme d'un 'wall' personnalisable et modérable__

###Le SmsWall ([/smswall](https://github.com/assobug/smswall/tree/master/smswall))

Cette version est compatible avec la version 1.1 de l'API Twitter

Le SmsWall vous permet d'afficher un mur contributif personnalisable qui agrège les tweets et les SMS envoyés par les utilisateurs en temps réel. Depuis l'admin, il permet à l'animateur de "piloter" le wall: mettre en valeur certains tweets ou leur contenu (photo, vidéo, ...) en les affichant dans une bulle, d'afficher ou masquer certains tweets, d'envoyer des messages "de services", ...


__Captation des tweets__

Deux solutions actuellement proposées:

Au choix (ne pas lancer les deux en même temps):

- PHP + API REST Twitter: présente dans l'admin du smswall (déconseillée)
- Python + API Streaming: que vous trouverez dans un dossier à part [/stream](https://github.com/assobug/smswall/tree/master/stream) (fortement conseillée)

[Documentation](https://github.com/assobug/smswall/tree/master/smswall#smswall)


###API Streaming ([/stream](https://github.com/assobug/smswall/tree/master/stream))

Cette petite application en Python branchée sur l'API Streaming de Twitter est en charge de récupérer tous les tweets correspondant à votre recherche (hashtags) en temps réel. Le grabber se lance en ligne de commande dans un terminal. Il ouvre une connexion authentifiée avec l'API de Twitter et doit rester connecté en tâche de fond.

Si vous optez pour cette solution il ne faudra pas lancer le grabber de tweet en PHP.

[Documentation](https://github.com/assobug/smswall/tree/master/stream#grabber)



###Captation de SMS ([/tasker](https://github.com/assobug/smswall/tree/master/tasker))

Il est désormais possible d'utiliser un appareil sous Android (avec carte SIM) pour récupérer les SMS. Ce script utilise l'application Tasker pour afficher sur le wall tous les SMS qui sont envoyés au mobile sur lequel tourne Tasker.

Cette solution est plus simple à mettre en place que la solution clé 3G + SmsEnabler. Les deux solutions sont compatibles avec le SmsWall, à vous de choisir la plus adaptée au matériel que vous avez à votre disposition.

[Documentation Tasker](https://github.com/assobug/smswall/tree/master/tasker#tasker) |
[Documentation SmsEnabler](https://github.com/assobug/smswall/tree/master/tasker#smsenabler)

---



__Amusez-vous bien :)__


Licence: CC BY-SA
