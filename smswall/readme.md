SmsWall
=======

Affichage temps réel de contributions envoyées par SMS et/ou Twitter sous forme d'un 'wall' personnalisable et modérable

Installation du SmsWall :
-------------------------

__Pré-requis :__

- Ouvrir un compte chez pusher.com et mettre de côté les informations de connexion à leur API. A la création de votre application Pusher pensez à cocher la case "Enable client events"

- Ouvrir un compte chez http://embed.ly/ et mettre de côté les informations de connexion à leur API


__Installation :__

- Posez les sources du wall (le dossier /smswall) sur votre serveur (local ou distant)
- Copiez le fichier de configuration d'exemple conf.inc.sample.php et renommez le en conf.inc.php.
- Modifiez le fichier conf.inc.php avec les paramètres de connexion à votre base de données ainsi que les informations qui vous ont été fournis par Embedly et Pusher
- Créez un base de donnée que vous nommerez 'smswall' (si vous disposez des droits root sur mysql vous pouvez vous rendre sur la page http://www.mondomaine.com/smswall/admin/init_db.php)
- Pour créer les tables rendez vous sur la page http://www.mondomaine.com/smswall/admin/init_tables.php

Votre SmsWall est opérationnel, si vous utilisez le grabber de tweet vous pouvez le lancer les messages devraient commencer à tomber sur le mur.

__Administration__

Rendez vous sur http://www.mondomaine.com/smswall/admin

__Wall public__

Dans un autre onglet de votre navigateur ou depuis une autre machine, rendez vous sur http://www.mondomaine.com/smswall


Accès protégé (.htaccess/.htpasswd) :
-------------------------------------

Un .htaccess est présent dans le dossier admin. Il faut le renommer (oter les __), lui associer un .htpasswd, et configurer le chemin d'accès du .htpasswd.

Sous *nix, pour créer le .htpasswd lancez la commande suivante :

>         htpasswd -c /path/du/.htpasswd wallmaster

Un prompt demandera le pass désiré pour le compte 'wallmaster'


