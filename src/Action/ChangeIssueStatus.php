<?php

declare(strict_types=1);

namespace App\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Transition;
use JiraRestApi\JiraException;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

class ChangeIssueStatus implements Action
{
    private Transition $transition;

    public function __construct(
        private IssueService $service,
        private LoggerInterface $logger
    ) {
    }

    public function setTransition(Transition $transition): ChangeIssueStatus
    {
        $this->transition = $transition;

        return $this;
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
