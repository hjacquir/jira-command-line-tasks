<?php

declare(strict_types=1);

namespace App\Condition;

use JiraRestApi\Issue\Issue;

class AlwaysTrue implements Condition
{
    public function isVerified(Issue $issue): bool
    {
        return true;
    }
}
