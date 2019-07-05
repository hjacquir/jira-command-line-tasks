#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Hj\Command\AssigneeCommand;
use Hj\Command\ChangeAssigneeByReporterCommand;
use Hj\Command\CommentAndAssignCommand;
use Hj\Command\CommentCommand;
use Hj\Command\CountCreatedResolved;
use Hj\Command\CountFieldWithOptionSelectedCommand;
use Hj\Command\CountIssueCommand;
use Hj\Command\GenerateStatsCommand;
use Hj\Command\GetBeginLastHdCommand;
use Hj\Command\GetIssueInfoCommand;
use Hj\Command\JqlGeneratorHelperCommand;
use Hj\Command\LabelGetterCommand;
use Hj\Command\UpdateDueDateCommand;
use Hj\Command\UpdateRootCauseCommand;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Application;

$logger = new Logger("console");
$output = "[%datetime%] %level_name% : %message%\n";
$formatter = new \Monolog\Formatter\LineFormatter($output);

try {
    $handler = new StreamHandler('php://stdout', Logger::DEBUG);
    $handler->setFormatter($formatter);
    $logger->pushHandler($handler);
} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}

$application = new Application();
$application->add(new AssigneeCommand('jqls/assigneeCommand.yaml', $logger));
$application->add(new ChangeAssigneeByReporterCommand('jqls/changeAssigneeByReporterCommand.yaml', $logger));
$application->add(new CommentCommand('jqls/commentCommand.yaml', $logger));
$application->add(new GetIssueInfoCommand('jqls/getIssueInfoCommand.yaml', $logger));
$application->add(new UpdateDueDateCommand('jqls/updateDueDateCommand.yaml', $logger));

try {
    $application->run();
} catch (Exception $e) {
    $logger->error($e->getMessage());
}