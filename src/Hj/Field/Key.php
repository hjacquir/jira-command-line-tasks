<?php

namespace Hj\Field;

use JiraRestApi\Issue\Issue;

class Key implements Field
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