<?php

namespace Hj\Loader;

use Hj\Jql\Condition;
use Hj\Jql\Jql;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueSearchResult;
use JiraRestApi\Issue\IssueService;

class JqlBasedLoader implements Loader
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var Jql
     */
    private $jql;

    /**
     * @var int
     */
    private $maxResults;

    /**
     * @var Condition
     */
    private $conditionMoveNextTicket;

    public function getJql() {
        return $this->jql;
    }

    /**
     * @param IssueService $service
     * @param string $jql
     * @param int $maxResults
     * @param Condition $conditionMoveNextTicket
     */
    public function __construct($service, $jql, $maxResults, Condition $conditionMoveNextTicket)
    {
        $this->service = $service;
        $this->jql = $jql;
        $this->maxResults = $maxResults;
        $this->conditionMoveNextTicket = $conditionMoveNextTicket;
    }

    public function load()
    {
        /** @var IssueSearchResult $issueSearchResults */
        $issueSearchResults = $this->service->search((string)$this->jql, 0, $this->maxResults);
        // on recupere toutes les issues correpondant à la JQL
        return $issueSearchResults->getIssues();
    }

    public function moveToNextTicket(Issue $issue)
    {
        $this->conditionMoveNextTicket->setContent('');
        $this->conditionMoveNextTicket->setContent('and issueKey > ' . $issue->key);
        $this->jql->addConditions($this->conditionMoveNextTicket);
    }

    public function getMaxResults()
    {
        return $this->maxResults;
    }
}