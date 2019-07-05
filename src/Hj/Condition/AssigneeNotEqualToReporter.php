<?php

namespace Hj\Condition;

use JiraRestApi\Issue\Issue;

class AssigneeNotEqualToReporter implements Condition
{
    public function isVerified(Issue $issue)
    {
        return !(null !== $issue->fields->assignee && $issue->fields->assignee->name === $issue->fields->reporter->name);
    }
}