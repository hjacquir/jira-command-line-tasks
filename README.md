# Jira Cloud Command Line Tasks

## Description
Run Jira Cloud tasks from the command line.

## Local development
- Install docker and docker compose
- Build and run image and container docker : `docker-compose up -d --build`.
- Go to the container : `docker exec -it app_jira_command_line bash`.
- Install all dependencies via Composer : `composer install`.
- Copy and paste the `vendor\lesstif\php-jira-rest-client\.env.example` file to the root of the project, naming 
it `.env` and filling in the correct parameters. **Warning: for the password you must use your token (JIRA API Token).**
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
* I run the command : `php console.php issue:update-status jqlsLocal/issue-update.yaml "Done" 1` 
to update issue status from : `To do` to `Done` and the output result is : 
```shell
$ php console.php issue:update-status jqlsLocal/issue-update.yaml 'Done' 1
[2023-06-13 10:42:25] The issue : TEST-2 change successfully status to : Done
[2023-06-13 10:42:25] INFO : project = "TEST"  and issueKey > TEST-1 AND issueKey in (TEST-1) order by issueKey ASC
```
The last line is the JQL formatted output

## List of available commands

### Issue
###### Update status
* look example above
###### Add comment
* adding a comment to an issue : `php console.php comment:add jqlsLocal/jql.yaml comment.php  2`
###### Update assignee
* Use the user accountId as parameter : `php console.php assignee:update jqlsLocal/assignee-update.yaml "557058:8ab8aa2f-a8b5-4e54-85a1-61d24575e374"  2`
* In this example, i use the account id : `557058:8ab8aa2f-a8b5-4e54-85a1-61d24575e374` for the issue `2`
* The account id can be found on the url of the user's profil
###### Get lists
* Export issues to excel file `xlsx/issues.xlsx`
* Example : `php console.php issue:get-fields jqlsLocal/jql.yaml`

### Cascade command 
* Allows you to execute a list of commands (look at :  `yamlConfigFileExamples/cascadeCommand.yaml`)