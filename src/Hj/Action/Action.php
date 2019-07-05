<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

/**
 * Perform an action on a ticket (add a comment, change the assignee ...)
 */
interface Action
{
    public function apply(Issue $issue);
}