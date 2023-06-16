#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Hj\Command\AssigneeCommand;
use Hj\Command\CascadeCommand;
use Hj\Command\CommentCommand;
use Hj\Command\GetIssueInfoCommand;
use Hj\Command\UpdateStatusCommand;
use JiraRestApi\Issue\IssueService;
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
$service = new IssueService();
$application->add(new AssigneeCommand($logger, $service));
$application->add(new CommentCommand($logger, $service));
$application->add(new GetIssueInfoCommand( $logger, $service));
$application->add(new UpdateStatusCommand($logger, $service));
$application->add(new CascadeCommand());

try {
    $application->run();
} catch (Exception $e) {
    $logger->error($e->getMessage());
}