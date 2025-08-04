# Project Name

![Symfony Version](https://img.shields.io/badge/Symfony-6.3%2B-blue.svg)
![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-purple.svg)
![CI Status](https://img.shields.io/github/actions/workflow/status/KasterborousConstellation/OrageWebSite/symfony.yml?branch=main&label=CI%20Build)
![License](https://img.shields.io/badge/license-MIT-green.svg)
# SGBD
## Connexion au SGBD
Pour faire fonctionner le SGDB, il suffit de connecter sa base de donnée dans le fichier .env puis ```DATABASE_URL```.  
Par exemple pour connecter une base MYSQL on a :  
```yaml
DATABASE_URL="mysql://$USER:$PASSWORD@localhost:3306/$DBNAME?serverVersion=$VERSION&charset=utf8mb4"
```  
Dans cet exemple les variables sont:  
\$USER : le nom d'utilisateur pour se connecter au SGBD  
\$PASSWORD : le mot de passe encoder correctement (voir la partie **Encodage**)
\$VERSION : la version du SGBD (voir la partie **Version**)  
  
Une fois le SGBD mis en place, voici comment remplir la base de donnée. 
## Remplissage de la BD (Migrations)
> [!CAUTION]
> La base de donnée doit être vierge pour que les migrations s'effectuent correctement. Les migrations sont là pour faire en sorte que la base de donnée ait la bonne forme pour que le site fonctionne

Les migrations se trouve dans le dossier ```migrations```. Une migration représente un changement dans la structure de la BD. Il est possible de créer des migrations pour remplir la base de donnée mais celles-ci ne sont pas auto-généré par Symfony  
(Ex : ```Version20250803175807.php```, crée à la main)

Afin de mettre à jour la base de donnée:
```bash
php bin/console doctrine:migrations:migrate
```
La base de donnée est en suite synchronisée avec la version présente dans les migrations.

> [!TIP]
> Si la base de donnée vient à être corrompue ou doit être changée. La procédure adaptée en developpement est:
    1) supprimer les tables   
    2) modifier les migrations par ex rajouter des entrées dans les tables (optionnel)  
    3) ```php bin/console doctrine:migrations:migrate```

## Encodage
Toutes les chaines de caractères présentent dans ```.env``` doivent être urlencodée. Pour cela, on peut passer par la fonction php suivante:
```php
rawurlencode(string) : string
```
Par exemple le mot de passe pour "FZJU8$@27Wc?" on a:
```php 
> echo rawurlencode("FZJU8$@27Wc?");
> FZJU8%24%4027Wc%3F
```
Ce mot de passe doit donc être renseigné dans ```.env``` par "FZJU8%24%4027Wc%3F".
> [!TIP]
> PHP dans le terminal via ```php -a```
## Version
Pour obtenir la version du SGBD via la console SQL:
```SQL
SELECT VERSION();
```
# Service Mail
Les mails ne peuvent pas être envoyé à une adresse spécifique en local car les accès mail ne sont pas autorisé. Le service OVH refuse que l'on envoye les mails depuis noreply@asso-orage.fr car c'est une demande qui vient de l'extérieur. Une fois en production on devrait pouvoir le faire.
## Configuration en local
Pour pouvoir tester les mails on utilisera un service Mail Virtuel : MailPit

### Etape 0 : Configurer le DSN 
Afin que Symfony sache comment on envoie les mails il faut changer le DSN. Ce DSN se trouve dans ```.env```.  
En particulier les chaines de caractères doivent être encodées comme pour le SGBD. (voir la section **Encoder**).  
MailPit tourne en local, et l'envoie des mails virtuels se fait sur le port 1025. On a donc dans ```.env```:  
```conf
MAILER_DSN=smtp://localhost:1025
```
### Etape 1 : Lancer MailPit

Pour lancer MailPit : 
```bash
./bin/mailpit
```
### Etape 2 : Ouvrir l'interface MailPit

L'interface MailPit se trouve via l'url : <http://localhost:8025>

## Configuration en production

Le DSN est théoriquement:
```conf
MAILER_DSN=smtp://noreply%asso-orage.fr:$PASSWORD@ssl0.ovh.net:587
```
Les mails sont alors envoyé par **noreply@asso-orage.fr**
