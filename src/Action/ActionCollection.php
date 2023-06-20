<?php

declare(strict_types=1);

namespace App\Action;

class ActionCollection
{
    /** @var Action[] */
    private array $actions;

    public function addAction(Action $action) {
        $this->actions[] = $action;
    }

    /** @return Action[] */
    public function getActions(): array
    {
        return $this->actions;
    }
}
