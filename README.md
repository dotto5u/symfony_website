## Utilisation

1. Intaller les dépendances `composer install`
2. Créer la base de données (changer le DATABASE_URL dans le .env si besoin)  
   `php bin/console doctrine:database:create`
3. Lancer les migrations `php bin/console doctrine:migration:migrate`
4. Importer les fixtures `php bin/console doctrine:fixtures:load`
5. Construire de CSS `php bin/console tailwind:build`

<u>Identifiants administrateur</u>

email : dotto.matis@gmail.com  
password : dotto123


<u>Identifiants utilisateurs</u>

email : ducret.thomas@yahoo.com  
password : ducret123

email : maaroufi.julien@yahoo.com  
password : maaroufi123

email : manick.luc@gmail.com  
password : manick123


## PHP CS-Fixer Tool

Pour utiliser l'outil PHP CS Fixer, il faut lancer la commande suivante : 

`php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src`