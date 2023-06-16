<?php

declare(strict_types=1);

namespace Hj\Loader;

use Hj\Jql\Jql;
use JiraRestApi\Issue\Issue;

interface Loader
{
    public function load();

    public function getMaxResults() : int;

    public function moveToNextTicket(Issue $issue);

    public function getJql() : Jql;
}
