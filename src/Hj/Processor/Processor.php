<?php

namespace Hj\Processor;

use Hj\Action\ActionCollection;
use Hj\Condition\Condition;
use Hj\Loader\Loader;
use JiraRestApi\Issue\IssueService;

class Processor
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var Condition
     */
    private $condition;

    /**
     * @var ActionCollection
     */
    private $actionCollection;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * Processor constructor.
     * @param IssueService $service
     * @param Condition $condition
     * @param ActionCollection $actionCollection
     * @param Loader $loader
     */
    public function __construct(IssueService $service, Condition $condition, ActionCollection $actionCollection, Loader $loader)
    {
        $this->service = $service;
        $this->condition = $condition;
        $this->actionCollection = $actionCollection;
        $this->loader = $loader;
    }

    public function stopProcess($issues)
    {
        // si le nombre de ticket dans ce lot est inférieur
        // au nombre maximum de résultats renvoyés par la requête -> on arrete le traitement
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
            // je vais passer au dernier élément du lot
            if ($key === count($issues) - 1) {
                // je sauvegarde la JQL avec le dernier id traité du lot
                $this->loader->moveToNextTicket($issue);
            }
        }

        // on verifie si on doit continuer
        if (false === $this->stopProcess($issues)) {
            // on passe au lot suivant
            echo "Je vais passer au lot suivant" . PHP_EOL;
            $this->process();
        }
    }
}