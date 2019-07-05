<?php

namespace Hj\Action;

use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\Issue;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;

class AddComment implements Action
{
    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * AddComment constructor.
     * @param IssueService $service
     * @param Comment $comment
     * @param Logger $logger
     */
    public function __construct(IssueService $service, Comment $comment, Logger $logger)
    {
        $this->service = $service;
        $this->comment = $comment;
        $this->logger = $logger;
    }


    /**
     * @param Issue $issue
     */
    public function apply(Issue $issue)
    {
        try {
            $this->service->addComment($issue->key, $this->comment);
            $this->logger->info("Commentaire ajouté au ticket " . $issue->key);
        } catch (JiraException $e) {
            $this->logger->error("Impossible d'ajouter le commentaire au ticket [{$e->getMessage()}]");
        } catch (\JsonMapper_Exception $e) {
            $this->logger->error("Impossible d'ajouter le commentaire au ticket [{$e->getMessage()}]");
        }
    }
}