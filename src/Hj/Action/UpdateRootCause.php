<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueField;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class UpdateRootCause implements Action
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var array
     */
    private $rootCauseCategory;

    /**
     * @var string
     */
    private $rootCause;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * UpdateRootCause constructor.
     *
     * @param IssueService $service
     * @param $rootCauseCategory
     * @param $rootCause
     * @param Logger $logger
     */
    public function __construct(IssueService $service, Array $rootCauseCategory, $rootCause, Logger $logger)
    {
        $this->service = $service;
        $this->rootCause = $rootCause;
        $this->rootCauseCategory = $rootCauseCategory;
        $this->logger = $logger;
    }

    /**
     * @param Issue $issue
     * @throws \Exception
     */
    public function apply(Issue $issue)
    {
        try {
            $issueField = new IssueField(true);
            $issueField->addCustomField('customfield_14126', $this->rootCauseCategory);
            $issueField->addCustomField('customfield_13005', $this->rootCause);
            $this->service->update($issue->key, $issueField);
            $this->logger->info("Le ticket " . $issue->key . " a maintenant pour root cause " . $this->rootCauseCategory['value']);
        } catch (JiraException $e) {
            $this->logger->error("Impossible de mettre à jour le root cause pour le ticket : " . $issue->key . ". Une erreur est survenue : " . " [{$e->getMessage()}]");
        }
    }
}