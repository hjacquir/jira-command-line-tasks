<?php

declare(strict_types=1);

namespace App\FieldValue;

use JiraRestApi\Issue\Issue;

class Summary implements FieldValue
{
    public function getValue(Issue $issue) : string
    {
        return $issue->fields->summary;
    }
}
