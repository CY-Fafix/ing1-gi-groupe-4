# Data_Challenge

Ce fichier README contient des instructions sur la façon de lancer le site web `index.php`.

## Prérequis

Avant de pouvoir lancer le site web, assurez-vous de disposer des éléments suivants :

- Un navigateur web (Google Chrome, Mozilla Firefox, Safari, etc.)
- Tous les fichiers du site web sans modifications.

## Configuration de la base de données avec votre compte

Pour configurer la base de données MySQL, suivez ces étapes :
1. Ouvrez l'invite de commande.
2. Naviguez en ligne de commande vers le dossier `Data_Challenge/`.
3. Assurez-vous que vous êtes dans le même dossier que les dossiers `public` et `src`
4. Modifiez le fichier `Database.php` situé dans `Data_Challenge/src/classes/Database.php` : vous pouvez modifier la valeur des variables $username et $password pour les remplacer par vos identifiants MySQL. 
5. Naviguez en ligne de commande vers le dossier situé dans  `Data_Challenge/src/sql/`.
6. Assurez-vous que vous êtes dans le même dossier que les fichiers `create_tables.sql` et `insert_data.sql`
4. Connectez-vous à votre serveur MySQL en utilisant la commande suivante :

    ```
    mysql -u votre_username -r votre_password
    ```

5. Une fois connecté, créez la base de données et les tables en exécutant le script `create_tables.sql` :

    ```
    source create_tables.sql
    ```

6. Ensuite, alimentez la base de données avec des données en exécutant le script `insert_data.sql` :

    ```
    source insert_data.sql
    ```

## Mise en place du serveur mail

1. Tout d'abord, ouvrez le terminal et tapez la commande :
    ```bash
    sudo apt autoremove sendmail
    ```
    puis installez postfix via la commande:
    ```bash
    sudo apt install postfix
    ```

2. Si vous avez une interface de configuration qui s'affiche, laissez tout par défaut sauf pour "postfix Configuration" que vous mettrez sur "Site Internet".

3. Tapez: `sudo nano /etc/postfix/sasl_passwd` puis validez. Dans la nouvelle interface tapez: `[smtp.gmail.com]:587 adresse.mail@gmail.com:motdepassed'application` (adresse gmail du gestionnaire qui peut envoyer des mails depuis le site et le mot de passe d'application du compte google lié à l'adresse gmail) puis enregistrez le fichier.

4. Tapez: `sudo nano /etc/postfix/main.cf` puis descendez dans le fichier qui apparaît jusqu'à voir "relayhost =". Tapez derrière ce égal: `[smtp.gmail.com]:587`.

5. Descendez tout en bas puis tapez les commandes suivantes:
    ```bash
    smtp_sasl_auth_enable = yes
    smtp_tls_security_level = encrypt
    smtp_sasl_tls_security_options = noanonymous
    smtp_sasl_password_maps = hash:/etc/postfix/sasl_passwd
    smtp_use_tls = yes
    smtp_tls_CAfile = /etc/ssl/certs/ca-certificates.crt
    ```
    puis sauvegardez.

6. Tapez: `sudo postmap /etc/postfix/sasl_passwd`. Pour plus de sécurité, tapez:
    ```bash
    sudo chmod 0600 /etc/postfix/sasl_passwd
    sudo chmod 0600 /etc/postfix/sasl_passwd.db
    ```

7. Cherchez votre fichier php.ini puis ouvrez-le (il se trouvait sur ma machine dans `/etc/php/7.2/apache2/php.ini`). Recherchez "smtp" dans ce fichier. Tapez devant "sendmail_path =" `"/usr/sbin/sendmail -t -i"` (tapez également les deux guillemets).

8. Relancez le service postfix: `sudo service postfix restart` puis lancez apache : `sudo service apache2 start`.

9. C'est bon! L'envoi de mail via le serveur de gmail est maintenant configuré sur votre machine!


## Mise en place de l'analyse de code

Pour lancer le web service REST :

1. Se déplacer dans `src/java/src/`

2. Si les 3 `.class` ne sont pas créés, exécuter les commandes suivantes :
    ```bash
    javac Fonctions.java
    javac -cp .:../../jar/* Serveur.java
    ```
3. Lancez ensuite le serveur :
    ```bash
    java -cp .:../../jar/* Serveur
    ```

4. À partir de là, un fichier Java s'exécute en arrière-plan pour faire tourner le web service REST.

5. Pour terminer le web service REST, ouvrez un autre terminal et exécutez la commandes suivante :
    ```bash
    ps -fC java
    ```
6. Trouvez le PID souhaité :
    ```bash
    kill -9 PID
    ```


## Lancement du site web

1. Ouvrez l'invite de commande.
2. Naviguez en ligne de commande vers le dossier `Data_Challenge/`.
3. Assurez-vous que vous êtes dans le même dossier que les dossiers `public` et `src`
4. Entrez la commande suivante dans la ligne de commande :
`php -S localhost:8080`
5. Maintenant, ouvrez le navigateur de votre choix.
6. Entrez dans la barre d'adresse :
`http://localhost:8080/public/index.php`
7. Vous êtes maintenant sur le site internet.

C'est tout ! Vous pouvez maintenant naviguer sur le site web en utilisant les liens et les boutons fournis sur la page.
Vous avez 3 comptes à disposition :
### Un compte Admin :
- mail : admin@gmail.com
- mdp : 1234
### Un compte Gestionnaire :
- mail : projeting1pafa@gmail.com 
- mdp : 1234
### Un compte Étudiant :
- mail : adri.jacob22@gmail.com
- mdp : 1234

