<?php

declare(strict_types=1);

namespace App\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Psr\Log\LoggerInterface;

class ChangeAssignee implements Action
{
    public function __construct(
        private IssueService $service,
        private string $accountId,
        private LoggerInterface $logger
    ) {
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->changeAssigneeByAccountId($issue->key, $this->accountId);
            $this->logger->info(
                sprintf(
                    'Assignment done to :  %s for issue %s',
                    $this->accountId,
                    $issue->key
                )
            );
        } catch (JiraException $e) {
            $this->logger->error(
                sprintf(
                    'Assignment fail to :  %s for issue %s. Error message : [%s]',
                    $this->accountId,
                    $issue->key,
                    $e->getMessage()
                )
            );
        }
    }
}
