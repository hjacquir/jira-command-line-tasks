<?php

namespace Hj\FieldValue\Reporter;

use Hj\FieldValue\FieldValue;
use JiraRestApi\Issue\Issue;

class Name implements FieldValue
{
    /**
     * @param Issue $issue
     * @return string
     */
    public function getValue(Issue $issue) : string
    {
        return $issue->fields->reporter->name ?? '';
    }
}