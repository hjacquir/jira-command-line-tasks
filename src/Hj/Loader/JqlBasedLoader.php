<?php

declare(strict_types=1);

namespace Hj\Loader;

use Hj\Jql\Condition;
use Hj\Jql\Jql;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;

class JqlBasedLoader implements Loader
{
    public function getJql() : Jql {
        return $this->jql;
    }

    public function __construct(
        private IssueService $service,
        private Jql $jql,
        private int $maxResults,
        private Condition $conditionMoveNextTicket
    ) {
    }

    public function load(): array
    {
        $issueSearchResults = $this->service->search((string)$this->jql, 0, $this->maxResults);
        // we retrieve all the issues corresponding to the JQL
        return $issueSearchResults->getIssues();
    }

    public function moveToNextTicket(Issue $issue)
    {
        $this->conditionMoveNextTicket->setContent('');
        $this->conditionMoveNextTicket->setContent('and issueKey > ' . $issue->key);
        $this->jql->addConditions($this->conditionMoveNextTicket);
    }

    public function getMaxResults() : int
    {
        return $this->maxResults;
    }
}
