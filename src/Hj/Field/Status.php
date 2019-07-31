<?php

namespace Hj\Field;

use JiraRestApi\Issue\Issue;

class Status implements Field
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