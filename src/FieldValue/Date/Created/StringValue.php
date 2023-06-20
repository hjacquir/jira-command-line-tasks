<?php

declare(strict_types=1);

namespace App\FieldValue\Date\Created;

use App\FieldValue\FieldValue;
use JiraRestApi\Issue\Issue;

class StringValue implements FieldValue
{
    public function __construct(private string $format)
    {
    }

    public function getValue(Issue $issue) : string
    {
        return $issue->fields->created->format($this->format);
    }
}
