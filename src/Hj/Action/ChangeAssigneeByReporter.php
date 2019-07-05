<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class ChangeAssigneeByReporter implements Action
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * ChangeAssigneeByReporter constructor.
     * @param IssueService $service
     * @param Logger $logger
     */
    public function __construct(IssueService $service, Logger $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->changeAssignee($issue->key, $issue->fields->reporter->name);
            $this->logger->info("Le rapporteur : " . $issue->fields->reporter->name . " a été assigné à son ticket : " . $issue->key);
        } catch (JiraException $e) {
            $this->logger->error("Assignation impossible du rapporteur " . $issue->fields->reporter->name . " à son ticket " . $issue->key . " [{$e->getMessage()}]");
        }
    }
}