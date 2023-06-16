<?php

declare(strict_types=1);

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Transition;
use JiraRestApi\JiraException;
use Monolog\Logger;

class ChangeIssueStatus implements Action
{
    private Transition $transition;

    public function __construct(
        private IssueService $service,
        private Logger $logger
    ) {
    }

    public function setTransition(Transition $transition)
    {
        $this->transition = $transition;
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->transition($issue->key, $this->transition);
            $this->logger->info(
                sprintf(
                    'The issue : %s change successfully status to : %s',
                    $issue->key,
                    $this->transition->transition['name']
                )
            );
        } catch (JiraException $e) {
            $this->logger->error(
                sprintf(
                    'The transition to %s for the issue %s fail. Error message : [%s]',
                    $this->transition->transition['name'],
                    $issue->key,
                    $e->getMessage()
                )
            );
        }

    }
}
