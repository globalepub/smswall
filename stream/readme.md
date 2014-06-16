Grabber
=======

L'objet de cette micro-application en Python est de créer une interface entre l'API Streaming de Twitter (v1.1) et le SmsWall

Si vous décidez de mettre en place cette solution il ne faut pas lancer en parallèle le grabber de tweet en PHP.

__Pourquoi Python ?__

L'API Streaming de Twitter requiert une connexion ouverte en permanence. Ce type de connexion ne doit pas être mis en place sous forme de requete HTTP dans le navigateur avec un rafraîchissement périodique comme nous le faisons avec /smswall/admin/register_tweet.php.

Pour se "brancher" sur le stream de Twitter il est préférable de lancer un script en ligne de commande qui tournera en permanence pendant la durée de l'utilisation du wall (imaginez un tuyaux ouvert entre Twitter et votre SmsWall avec un flux constant d'information entre les deux). PHP n'est pas le plus adapté pour ce genre de travail alors que Python répond tout à fait à cette demande.

__Pré-requis__

- Ouvrir un compte Twitter qui sera utilisé pour l'authentification.

- Créer une application Twitter rattachée à ce compte: Rendez vous sur https://dev.twitter.com/apps, authentifiez-vous puis créez une nouvelle application

- Ouvrir un compte pusher.com (il s'agit du même compte que pour le smswall)

- Python 2.7 avec Virtualenv et PIP installés et fonctionnels

- Mysql pour Python (voir bas de page)

_Pour plus d'information sur Virtualenv et son utilisation vous pouvez consulter la très bonne introduction d'Armin Ronacher pour l'installation d'un Flask : http://flask.pocoo.org/docs/installation_


Installation :
--------------

__Création de l'environnement__


    ~/ $ cd smswall
    ~/smswall $ virtualenv env


__Activation__


    ~/smswall $ . env/bin/activate


__Installation des paquets__

    (env) ~/smswall $ cd stream
    (env) ~/smswall/stream $ pip install -r requirements.txt


__Configuration__

- Copiez le fichier de configuration d'exemple config.sample.py et renommez le en config.py.
- Modifiez config.py en ajoutant vos paramètres de connexion à votre base de donnée, ainsi que vos paramètres de connexion à l'application Twitter et à votre compte Pusher.

__Création de la base de donnée__

Cette opération n'est à effectuer qu'une seule fois. Soit avec le script smswall/init_db.php dans un navigateur soit avec les commandes ci-dessous :

Dans un terminal, lancez un shell mysql :

    root@localhost:~# mysql -u root -ppassword

    mysql> CREATE DATABASE IF NOT EXISTS `smswall` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

Création du user wally :

    mysql> GRANT ALL PRIVILEGES ON smswall.* TO wally@localhost IDENTIFIED BY 'k4m0ul0x';
    mysql> exit
    bye

Création des tables et init de la configuration du wall :

    (env) ~/smswall $ cd stream
    (env) ~/smswall/stream $ python init_db.py


Lancement de l'application
--------------------------

Démarrez le grabber en lançant cette commande :

    (env) ~/smswall/stream $ python grabber.py

Pour arrêter proprement le processus en cours d'exécution, appuyez sur Ctrl+C.


Problèmes courant
-----------------

ImportError: No module named MySQLdb

Si vous rencontrez cette erreur c'est que MySQL n'est pas correctement installé pour Python sur votre machine.

    $ sudo apt-get install python-dev libmysqlclient-dev

Plus d'infos :

- http://mysql-python.blogspot.fr/2012/11/is-mysqldb-hard-to-install.html
- http://codeinthehole.com/writing/how-to-set-up-mysql-for-python-on-ubuntu/
