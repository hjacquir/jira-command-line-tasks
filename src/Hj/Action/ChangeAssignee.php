<?php

declare(strict_types=1);

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class ChangeAssignee implements Action
{
    public function __construct(
        private IssueService $service,
        private string $assigneeName,
        private Logger $logger
    ) {
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->changeAssignee($issue->key, $this->assigneeName);
            $this->logger->info(
                sprintf(
                    'Assignment done to :  %s for issue %s',
                    $this->assigneeName,
                    $issue->key
                )
            );
        } catch (JiraException $e) {
            $this->logger->error(
                sprintf(
                    'Assignment fail to :  %s for issue %s. Error message : [%s]',
                    $this->assigneeName,
                    $issue->key,
                    $e->getMessage()
                )
            );
        }
    }
}
