<?php

declare(strict_types=1);

namespace App\Loader;

use App\Jql\Jql;
use JiraRestApi\Issue\Issue;

interface Loader
{
    public function load();

    public function getMaxResults() : int;

    public function moveToNextTicket(Issue $issue);

    public function getJql() : Jql;
}
