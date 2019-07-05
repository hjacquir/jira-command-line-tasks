<?php

namespace Hj\Condition;

use JiraRestApi\Issue\Issue;

interface Condition
{
    public function isVerified(Issue $issue);
}