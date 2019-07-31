<?php

namespace Hj\Field;

use JiraRestApi\Issue\Issue;

class Summary implements Field
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