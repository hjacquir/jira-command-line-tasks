<?php

namespace Hj\FieldValue;

use JiraRestApi\Issue\Issue;

class Key implements FieldValue
{
    /**
     * @param Issue $issue
     * @return string
     */
    public function getValue(Issue $issue) : string
    {
        return $issue->key;
    }
}