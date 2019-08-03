<?php

namespace Hj\FieldValue\Assignee;

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
        return $issue->fields->assignee->name;
    }
}