# jira-command-line-tasks

## Prérequis

- PHP 7

## Description
Exécuter des tâches Jira en ligne de commande.

## Usage
- Installer toutes les dépendances via Composer :
`composer install` ou `php composer.php install`

- Copier coller le fichier `vendor\lesstif\php-jira-rest-client\.env.example` à la racine du projet en le nommant `.env` et en renseignant les bons paramètres. Attention : pour le mot de passe il faut utiliser votre jeton (JIRA API Token).

- Lancer la commande : `php console.php` pour voir la liste des commandes disponibles
