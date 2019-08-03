<?php

namespace Hj\FieldValue;

use JiraRestApi\Issue\Issue;

interface FieldValue
{
    /**
     * @param Issue $issue
     * @return mixed
     */
    public function getValue(Issue $issue);

}