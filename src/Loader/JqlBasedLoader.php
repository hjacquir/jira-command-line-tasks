<?php

declare(strict_types=1);

namespace App\Loader;

use App\Jql\Condition;
use App\Jql\Jql;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;

class JqlBasedLoader implements Loader
{
    public function __construct(
        private IssueService $service,
        private Jql $jql,
        private int $maxResults,
        private Condition $conditionMoveNextTicket
    ) {
    }

    public function getJql() : Jql {
        return $this->jql;
    }

    public function load(): array
    {
        $issueSearchResults = $this->service->search(
            (string)$this->jql,
            0,
            $this->maxResults
        );
        // we retrieve all the issues corresponding to the JQL
        return $issueSearchResults->getIssues();
    }

    public function moveToNextTicket(Issue $issue)
    {
        $this->conditionMoveNextTicket->setContent('')
            ->setContent('and issueKey > ' . $issue->key);
        $this->jql->addConditions($this->conditionMoveNextTicket);
    }

    public function getMaxResults() : int
    {
        return $this->maxResults;
    }
}
