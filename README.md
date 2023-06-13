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

## Examples

### Update an issue status
* I create a local yaml jql file in the root of the project :  `jqlsLocal/issue-update.yaml`
* The `jqlsLocal/issue-update.yaml` contains : 
```yaml
project:
    name: 'TEST'
    operator: '='
    issueSuffix: TEST
conditions:
    - ''
expressions:
    - 'order by issueKey ASC'
```
* I run the command : `php console.php issue:update-status jqlsLocal/issue-update.yaml "Terminé(e)" 1` 
to update issue status from : `A faire` to `Terminé(e)` and the output result is : 
```shell
$ php console.php issue:update-status jqlsLocal/issue-update.yaml "Terminé(e)" 1
[2023-06-13 10:42:25] INFO : Le ticket TEST-1 est passé à l'état : Terminé(e)
[2023-06-13 10:42:25] INFO : project = "TEST"  and issueKey > TEST-1 AND issueKey in (TEST-1) order by issueKey ASC
```
The last line is the JQL formatted output
