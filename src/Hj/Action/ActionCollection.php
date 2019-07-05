<?php

namespace Hj\Action;

class ActionCollection
{
    /**
     * @var Action[]
     */
    private $actions;

    /**
     * @param Action $action
     */
    public function addAction(Action $action) {
        $this->actions[] = $action;
    }

    /**
     * @return Action[]
     */
    public function getActions()
    {
        return $this->actions;
    }
}