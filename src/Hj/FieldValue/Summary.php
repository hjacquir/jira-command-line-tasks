<?php

namespace Hj\FieldValue;

use JiraRestApi\Issue\Issue;

class Summary implements FieldValue
{
    /**
     * @param Issue $issue
     * @return string
     */
    public function getValue(Issue $issue) : string
    {
        return $issue->fields->summary;
    }
}