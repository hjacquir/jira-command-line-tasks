# jira-command-line-tasks

## Prerequisites

- PHP 7.3
- Extensions (php.ini) : 
`extension=curl, extension=fileinfo, extension=gd2, extension=mbstring, extension=openssl`

## Description
Run Jira Cloud tasks from the command line.

## Use
- Install all dependencies via Composer :
`composer install` or `php composer.php install`.

- Copy and paste the `vendor\lesstif\php-jira-rest-client\.env.example` file to the root of the project, naming it `.env` and filling in the correct parameters. Warning: for the password you must use your token (JIRA API Token).

- Run the command: `php console.php` to see the list of available commands.
