<?php

namespace Hj\Field;

use JiraRestApi\Issue\Issue;

interface Field
{
    /**
     * @param Issue $issue
     * @return mixed
     */
    public function getValue(Issue $issue);
}