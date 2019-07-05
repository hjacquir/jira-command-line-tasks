<?php

namespace Hj\Condition;

use JiraRestApi\Issue\Issue;

class AlwaysTrue implements Condition
{
    public function isVerified(Issue $issue)
    {
        return true;
    }
}