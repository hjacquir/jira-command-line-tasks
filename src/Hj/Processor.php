<?php

declare(strict_types=1);

namespace Hj;

use Hj\Action\ActionCollection;
use Hj\Condition\Condition;
use Hj\Loader\Loader;

class Processor
{
    public function __construct(
        private Condition $condition,
        private ActionCollection $actionCollection,
        private Loader $loader
    ) {
    }

    public function stopProcess(array $issues) : bool
    {
        // if the number of tickets in this batch is lower
        // to the maximum number of results returned by the query -> we stop processing
        return count($issues) < $this->loader->getMaxResults();
    }

    public function process()
    {
        $issues = $this->loader->load();

        foreach ($issues as $key => $issue) {
            if ($this->condition->isVerified($issue)) {
                foreach ($this->actionCollection->getActions() as $action) {
                    $action->apply($issue);
                }
            }
            // I will go to the last element of the batch
            if (count($issues) - 1 === $key) {
                // I save the JQL with the last processed id of the batch
                $this->loader->moveToNextTicket($issue);
            }
        }

        // we check if we should continue
        if (false === $this->stopProcess($issues)) {
            // we go to the next batch
            echo "We go to the next batch " . PHP_EOL;
            $this->process();
        }
    }
}
