<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class ChangeAssignee implements Action
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var string
     */
    private $assigneeName;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * ChangeAssignee constructor.
     * @param IssueService $service
     * @param string $assigneeName
     * @param Logger $logger
     */
    public function __construct(IssueService $service, $assigneeName, Logger $logger)
    {
        $this->service = $service;
        $this->assigneeName = $assigneeName;
        $this->logger = $logger;
    }


    /**
     * @param Issue $issue
     */
    public function apply(Issue $issue)
    {
        try {
            $this->service->changeAssignee($issue->key, $this->assigneeName);
            $this->logger->info("Assignation effectuée de " . $this->assigneeName . " au ticket " . $issue->key);
        } catch (JiraException $e) {
            $this->logger->error("Assignation impossible de " . $this->assigneeName . " au ticket " . $issue->key . " [{$e->getMessage()}]");
        }
    }
}