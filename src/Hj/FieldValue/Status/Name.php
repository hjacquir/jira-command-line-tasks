<?php

namespace Hj\FieldValue\Status;

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
        return $issue->fields->status->name;
    }
}