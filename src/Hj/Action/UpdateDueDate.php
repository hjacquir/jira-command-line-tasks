<?php

namespace Hj\Action;

use DateTime;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class UpdateDueDate implements Action
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var array
     */
    private $mappingDueDates;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * UpdateDueDate constructor.
     * @param IssueService $service
     * @param array mapping with key = issue key and value = due date $dueDates
     * @param Logger $logger
     */
    public function __construct(IssueService $service, $mappingDueDates, Logger $logger)
    {
        $this->service = $service;
        $this->mappingDueDates = $mappingDueDates;
        $this->logger = $logger;
    }

    /**
     * @param Issue $issue
     * @throws \Exception
     */
    public function apply(Issue $issue)
    {
        foreach ($this->mappingDueDates as $issueKey => $dueDate) {
            if ($issue->key === $issueKey) {
                try {
                    $issueField = new IssueField(true);
                    $issueField->setDueDate(new DateTime($dueDate));
                    $this->service->update($issueKey, $issueField);
                    $this->logger->info("Le ticket " . $issue->key . " a maintenant pour date d'échéance " . $dueDate);
                } catch (JiraException $e) {
                    $this->logger->error("Impossible de mettre à jour la date d'échéance pour le ticket : " . $issue->key . ". Une erreur est survenue : " . " [{$e->getMessage()}]");
                }
            }
        }

    }
}