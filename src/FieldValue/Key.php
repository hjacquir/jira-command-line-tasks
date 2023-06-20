<?php

declare(strict_types=1);

namespace App\FieldValue;

use JiraRestApi\Issue\Issue;

class Key implements FieldValue
{
    public function getValue(Issue $issue) : string
    {
        return $issue->key;
    }
}
