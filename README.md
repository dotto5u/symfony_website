## Utilisation

1. Intaller les dépendances : `composer install`
2. Créer la base de données (changer le DATABASE_URL dans le .env si besoin) : `php bin/console doctrine:database:create`
3. Lancer les migrations : `php bin/console doctrine:migration:migrate`
4. importer les fixtures : `php bin/console doctrine:fixtures:load`
5. Construire de CSS `php bin/console tailwind:build`


# Cours Symfony

https://symfony-course.vercel.app/


## PHP CS-Fixer Tool

Pour utiliser l'outil PHP CS Fixer, il faut lancer la commande suivante : 

`php tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src`


## Build Tailwind CSS

Pour build le CSS de Tailwind, il faut lancer la commande suivante :

`php bin/console tailwind:build -w`