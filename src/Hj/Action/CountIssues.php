<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class CountIssues implements Action
{
    /**
     * @var int
     */
    private $counter = 0;

    public function apply(Issue $issue)
    {
        $this->counter++;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter($counter)
    {
        $this->counter = $counter;
    }
}