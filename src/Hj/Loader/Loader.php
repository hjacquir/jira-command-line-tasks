<?php

namespace Hj\Loader;

use Hj\Jql\Jql;
use JiraRestApi\Issue\Issue;

interface Loader
{
    public function load();

    /**
     * @return int
     */
    public function getMaxResults() : int ;

    /**
     * @param Issue $issue
     */
    public function moveToNextTicket(Issue $issue);

    /**
     * @return Jql
     */
    public function getJql() : Jql;
}