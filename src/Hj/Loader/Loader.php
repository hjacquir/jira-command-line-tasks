<?php

namespace Hj\Loader;

use JiraRestApi\Issue\Issue;

interface Loader
{
    public function load();

    public function getMaxResults();

    public function moveToNextTicket(Issue $issue);

    public function getJql();
}