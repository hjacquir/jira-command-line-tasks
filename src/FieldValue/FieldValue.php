<?php

declare(strict_types=1);

namespace App\FieldValue;

use JiraRestApi\Issue\Issue;

interface FieldValue
{
    public function getValue(Issue $issue);
}
