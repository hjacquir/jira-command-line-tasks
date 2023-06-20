<?php

declare(strict_types=1);

namespace App\FieldValue\Assignee;

use App\FieldValue\FieldValue;
use JiraRestApi\Issue\Issue;

class Name implements FieldValue
{
    public function getValue(Issue $issue) : string
    {
        return $issue->fields->assignee->name ?? '';
    }
}
