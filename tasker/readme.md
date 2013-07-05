Tasker
======

Ce tutorial vous permettra de mettre en place un 'grabber' de SMS à l'aide d'un téléphone sous Android et de l'application Tasker (Version pour Android >= 4.0).

Vous pouvez aussi importer directement grabber.xml dans Tasker et modifier les différents paramètres en fonction de votre utilisation.

__Tasker :__

Tasker est une application Android qui vous permet de créer et d’exécuter des taches sur votre mobile en fonction d'un ou de plusieurs contextes particuliers. C'est une véritable trousse à outil pour personnaliser et automatiser votre mobile très finement et qui peut remplacer avantageusement une multitude de petites applications qui ne font pas toujours exactement ce qu'on leur demande et finissent par polluer plus qu'autre chose votre téléphone.

Tasker est un application payante (2,99€) mais qui vous rendra bien des services en dehors du grabber de SMS. Il existe aussi une version d'évaluation qui ne semble pas limitée dans ses fonctionnalités mais qui expire au bout de 7 jours, ce qui peut être largement suffisant pour vous convaincre de payer la licence :)

- Google Store : <https://play.google.com/store/apps/details?id=net.dinglisch.android.taskerm>
- Site web : <http://tasker.dinglisch.net/>
- Guide, how-to : <http://www.pocketables.com/2013/03/overview-of-pocketables-tasker-articles.html>

Une fois installée sur votre mobile, lançons nous dans le vif du sujet !


Création du profil :
--------------------

Commencez par créer un nouveau profil en cliquant sur le gros bouton + en bas de l'écran d'accueil de Tasker. Donnez lui un nom, par exemple: "Réception de SMS"

Ensuite il faut spécifier le contexte principal. Nous voulons que le téléphone réagisse à l'arrivée de chaque SMS. Nous allons donc choisir __Evénement__ comme premier contexte puis dans la catégorie __Téléphone__ nous sélectionnons le type d'évènement : __SMS reçu__

Dans la page d'édition de l'évènement, vérifiez bien que le champ Type est configuré sur __Tous__ ou éventuellement __SMS__ puisque les MMS ne sont actuellement pas traités.

__Enfin, pour valider puis revenir à l'écran principal, cliquez sur le logo Tasker en haut à gauche.__


Création de la tâche :
----------------------

Tasker vous invitera ensuite à choisir ou à créer une nouvelle Tâche. Nous allons donc choisir __Nouvelle tâche__, lui choisir un nom ( "Grabber" par exemple ) puis valider.

Nous nous retrouvons sur un écran vierge où nous allons créer plusieurs actions qui seront exécutées à chaque réception de SMS.

Note: nous décidons ici d'envoyer le contenu du message en GET sur une adresse http public dans le cadre d'une installation de type SmsWall. En fonction de votre utilisation, vos besoins ne seront pas forcément les mêmes, les actions seront différentes.
N'hésitez pas à consulter la documentation et le wiki de Tasker en cas de besoin !


####1 - Affectation de variable :####

Tasker accède pour vous aux variables locales du système de votre téléphone. La date, l'heure, l'état du téléphone ou bien encore le contenu du dernier SMS reçu sont donc disponible.

Nous allons créer notre propre variable pour y stocker le corps du message contenu dans le SMS pour pouvoir par la suite l'utiliser à notre compte :

- Commencez par créer une nouvelle action en cliquant sur le bouton + en bas de l'écran
- Choisissez la catégorie : __Variable__
- Choisissez ensuite l'action à effectuer avec cette variable : __Affecter une variable__
- Dans la page d'édition de l'action :
     - Choisissez un nom. Celui-ci doit impérativement commencer par un %. Par exemple : __%BODY__
     - Dans le deuxième champ 'A' nous allons choisir le contenu à affecter à notre variable. Vous pouvez cliquer sur l'icone à droite du champ pour choisir dans la liste complète de toutes les variables locales disponibles (La liste est longue ...). Choisissez __Texte - Corps__. Le champ doit s'être automatiquement rempli avec la chaîne de caractère __%SMSRB__
     - Validez en cliquant sur le logo Tasker en haut à gauche


####2 - Encodage de la variable####

Avant de pouvoir envoyer la variable en GET nous devons l'urlencoder. Tasker nous permet de manipuler les variables à notre convenance :

- Créez une nouvelle action
- Choisissez la catégorie : __Variable__
- Choisissez ensuite l'action __Conversion de variable__
- Dans la page d'édition de l'action :
     - Saisissez le nom de votre variable créée précédemment (%BODY dans notre exemple)
     - Ensuite choisissez __URL Encode__ dans la liste du champ Fonction
     - Validez en cliquant sur le logo Tasker en haut à gauche


####3 - Envoi au serveur####

Ces informations sont spécifiques au SmsWall et ne sont données qu'à titre indicatif, pour l'exemple. Vous pouvez en avoir une utilisation toute autre, à vous de changer les différents paramètres à votre convenance.

( Nous partons du principe que votre SmsWall est installé dans un dossier 'smswall' à la racine de votre serveur )

- Créez une nouvelle action
- Choisissez la catégorie : __Réseau__
- Choisissez ensuite l'action __Get HTTP__
- Dans la page d'édition de l'action :
     - Serveur:Port : __www.nomdomaine.com__
     - Chemin : __smswall/registersms.php__
     - Attributs : __text=%BODY__
     - Tous les autres champs sont laissés vide par défaut
     - Validez en cliquant sur le logo Tasker en haut à gauche


Activation de la tâche :
------------------------

Une fois le profil et la tâche associée créés, révenez à l'onglet principal 'Profils' et vérifiez que votre profil est activé.

__Activation de Tasker__

Pour que Tasker soit actif en tâche de fond sur votre mobile il faut l'activer, ce qui n'est pas encore forcément le cas. Vérifiez que l'icône Tasker en haut à gauche de l'écran est en couleur (l'éclair du logo doit être jaune / orange). Si le logo est monochrome, appuyez longuement sur le logo. Un message devrait vous indiquer 'Enabled' et le logo devrait se colorer ...

Il ne vous reste plus qu'à tester le bon fonctionnement de la tâche en vous envoyant à vous même un SMS :)



SmsEnabler
==========

Il est aussi possible d'utiliser SmsEnabler sur un PC sous Windows avec une clé 3G pour récupérer les SMS.
...
