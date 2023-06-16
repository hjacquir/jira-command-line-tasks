<?php

declare(strict_types=1);

namespace Hj\Condition;

use JiraRestApi\Issue\Issue;

interface Condition
{
    public function isVerified(Issue $issue): bool;
}
