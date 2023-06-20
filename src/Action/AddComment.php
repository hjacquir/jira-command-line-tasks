<?php

declare(strict_types=1);

namespace App\Action;

use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use Psr\Log\LoggerInterface;

class AddComment implements Action
{
    public function __construct(
        private IssueService $service,
        private  Comment $comment,
        private LoggerInterface $logger
    ) {
    }

    public function apply(Issue $issue)
    {
        try {
            $this->service->addComment($issue->key, $this->comment);
            $this->logger->info(sprintf('Comment added to issue : %s', $issue->key));
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Adding comment to issue %s failed. Error : [%s]', $issue->key, $e->getMessage()));
        }
    }
}
