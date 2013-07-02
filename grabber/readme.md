Grabber
=======

L'objet de cette micro-application en Python est de créer une interface entre l'API Streaming de Twitter (v1.1) et le SmsWall

__Pourquoi Python ?__

Alors que le smswall est en PHP, le grabber lui est en Python. La raison est assez simple: contrairement à l'ancienne API, l'API Streaming requiert une connexion ouverte en permanence (long polling). Ce type de connexion ne doit pas être mis en place sous forme de requete HTTP dans le navigateur avec un rafraichissement périodique comme nous le faisions avec les anciennes versions du SmsWall.

Il est préférable de lancer un script en ligne de commande qui tournera en permanence pendant la durée de l'utilisation du wall (imaginez un tuyaux ouvert entre Twitter et votre SmsWall avec un flux constant d'information entre les deux). PHP n'est pas le plus adapté pour ce genre de travail alors que Python répond tout à fait à cette demande.

__Pré-requis__

- Ouvrir un compte Twitter qui sera utilisé pour l'authentification

Rendez vous sur https://dev.twitter.com/apps, authentifiez-vous puis créez une nouvelle application

- Un compte pusher.com (il s'agit du même compte que pour le smswall)

- Python 2.7 avec Virtualenv et PIP installés et fonctionnels

Pour plus d'information sur Virtualenv et son utilisation vous pouvez consulter la très bonne introduction d'Armin Ronacher pour l'installation d'un Flask (qui est un des packets utilisés par ce script)

Installation :
--------------

__Création de l'environnement__

'''
~/ cd smswall
~/smswall $ virtualenv env
'''

__Activation__

'''
~/smswall $ env/bin/activate
'''

__Installation des paquets__

'''
(env) ~/smswall $ pip install -r requirements.txt
'''

__Lancement de l'application__

- Copiez le fichier de configuration d'exemple config.sample.py et renommez le en config.py.
- Puis modifiez config.py en ajoutant vos paramètres de connexion à votre base de donnée ainsi que vos paramètres de connexion à Pusher.

'''
(env) dweez@dizou: ~/smswall/grabber $ python grabber.py start -uuser -ppassword -s 245.245.22.22 -b smswall
'''

__Problèmes courant__

ImportError: No module named MySQLdb

Si vous rencontrez cette erreur c'est que MySQL n'est pas correctement installé pour Python sur votre machine.

'''
$ sudo apt-get install mysql-server mysql-client python-mysqldb
'''