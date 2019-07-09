<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Transition;
use JiraRestApi\JiraException;
use Monolog\Logger;

class ChangeIssueStatus implements Action
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
     * @var Transition
     */
    private $transition;

    /**
     * AddComment constructor.
     * @param IssueService $service
     * @param Logger $logger
     */
    public function __construct(IssueService $service, Logger $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    /**
     * @param Transition $transition
     */
    public function setTransitionName(Transition $transition)
    {
        $this->transition = $transition;
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->transition($issue->key, $this->transition);
            $this->logger->info("Le ticket " . $issue->key . " est passé à l'état " . $this->transition->id);
        } catch (JiraException $e) {
            $this->logger->error("Impossible de faire la transition au ticket [{$e->getMessage()}]");
        }

    }
}