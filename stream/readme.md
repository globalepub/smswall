Grabber
=======

L'objet de cette micro-application en Python est de créer une interface entre l'API Streaming de Twitter (v1.1) et le SmsWall

Si vous décidez de mettre en place cette solution il ne faut pas lancer en parralèle le grabber de tweet en PHP.

__Pourquoi Python ?__

L'API Streaming de Twitter requiert une connexion ouverte en permanence. Ce type de connexion ne doit pas être mis en place sous forme de requete HTTP dans le navigateur avec un rafraichissement périodique comme nous le faisons avec /smswall/admin/register_tweet.php.

Pour se "brancher" sur le stream de Twitter il est préférable de lancer un script en ligne de commande qui tournera en permanence pendant la durée de l'utilisation du wall (imaginez un tuyaux ouvert entre Twitter et votre SmsWall avec un flux constant d'information entre les deux). PHP n'est pas le plus adapté pour ce genre de travail alors que Python répond tout à fait à cette demande.

__Pré-requis__

- Ouvrir un compte Twitter qui sera utilisé pour l'authentification.

- Créer une application Twitter rattachée à ce compte: Rendez vous sur https://dev.twitter.com/apps, authentifiez-vous puis créez une nouvelle application

- Ouvrir un compte pusher.com (il s'agit du même compte que pour le smswall)

- Python 2.7 avec Virtualenv et PIP installés et fonctionnels

_Pour plus d'information sur Virtualenv et son utilisation vous pouvez consulter la très bonne introduction d'Armin Ronacher pour l'installation d'un Flask (qui est un des paquets utilisés par le grabber) : http://flask.pocoo.org/docs/installation_

- Pas de script de création de la base de donnée côté Python pour l'instant. Se référer au script d'install de la partie PHP (@todo: lien direct)


Installation :
--------------

__Création de l'environnement__


    ~/ $ cd smswall
    ~/smswall $ virtualenv env


__Activation__


    ~/smswall $ env/bin/activate


__Installation des paquets__


    (env) ~/smswall $ pip install -r requirements.txt


__Configuration__

- Copiez le fichier de configuration d'exemple config.sample.py et renommez le en config.py.
- Modifiez config.py en ajoutant vos paramètres de connexion à votre base de donnée, ainsi que vos paramètres de connexion à l'application Twitter et à votre compte Pusher.

__Création de la base de donnée__

Cette opération n'est à effectuer qu'une seule fois. Soit avec le script smswall/init_db.php dans un navigateur soit avec les commandes ci-dessous :

Dans un terminal, lancez un shell mysql :

	root@localhost:~# mysql -u root -ppassword

	mysql> CREATE DATABASE smswall;

Création du user wally :

	mysql> GRANT ALL PRIVILEGES ON smswall.* TO wally@localhost IDENTIFIED BY 'k4m0ul0x';
	mysql> exit
	bye

Création des tables et init de la configuration du wall :

	(env) ~/smswall $ cd grabber
	(env) ~/smswall/grabber $ python init_db.py


__Lancement de l'application__

- démarrez le grabber en lancant cette commande :

    (env) ~/smswall/grabber $ python grabber.py


__Problèmes courant__

ImportError: No module named MySQLdb

Si vous rencontrez cette erreur c'est que MySQL n'est pas correctement installé pour Python sur votre machine.


    $ sudo apt-get install mysql-server mysql-client python-mysqldb
