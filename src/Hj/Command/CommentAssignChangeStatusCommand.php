<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\AddComment;
use Hj\Action\ChangeAssignee;
use Hj\Action\ChangeIssueStatus;
use Hj\Condition\AlwaysTrue;
use Hj\Exception\EmptyCommentException;
use Hj\Exception\EmptyStringException;
use Hj\Exception\FileNotFoundException;
use Hj\Helper\CommentFormatter;
use Hj\Jql\Condition;
use Hj\Jql\Jql;
use Hj\JqlConfigurator;
use Hj\Loader\JqlBasedLoader;
use Hj\Processor\Processor;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\Issue\Transition;
use JiraRestApi\JiraException;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommentAssignChangeStatusCommand extends Command
{
    /**
     * @var string
     */
    private $yamlFile;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * CommentCommand constructor.
     * @param string $yamlFile
     * @param Logger $logger
     */
    public function __construct($yamlFile, Logger $logger)
    {
        parent::__construct();
        $this->yamlFile = $yamlFile;
        $this->logger = $logger;
    }

    protected function configure()
    {

        $this->setName('helper:comment-assign-change-status');
        $this
            ->addArgument(
                'id',
                InputArgument::REQUIRED,
                'Issue Id'
            );
        $this
            ->addArgument(
                'assignee',
                InputArgument::REQUIRED,
                'Assignee name'
            );
        $this
            ->addArgument(
                'commentFilePath',
                InputArgument::REQUIRED,
                'The path to the php file that load the comment'
            );
        $this
            ->addArgument(
                'newStatus',
                InputArgument::REQUIRED,
                'The issue new status'
            );

        $this->setHelp('Comment, assign and change issue status.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws EmptyStringException
     * @throws FileNotFoundException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $assigneeName = $input->getArgument('assignee');
        $commentFilePath = $input->getArgument('commentFilePath');
        $newStatus = $input->getArgument('newStatus');

        try {
            $sr = new IssueService();
            $condition = new AlwaysTrue();
            $comment = new Comment();
            $commentFormatter = new CommentFormatter($commentFilePath);
            $commentBody = $commentFormatter->getComment();
            $comment->setBody($commentBody);
            $commentAction = new AddComment($sr, $comment, $this->logger);
            $assigneeAction = new ChangeAssignee($sr, $assigneeName, $this->logger);
            $changeStatusAction = new ChangeIssueStatus($sr, $this->logger);
            $transition = new Transition();
            $transition->setTransitionName($newStatus);
            $changeStatusAction->setTransition($transition);
            $collection = new ActionCollection();
            $collection->addAction($commentAction);
            $collection->addAction($assigneeAction);
            $collection->addAction($changeStatusAction);

            $jql = new Jql([$id]);
            $configurator = new JqlConfigurator($jql);
            $jql = $configurator->configure($this->yamlFile);

            $conditionMoveToNextTicket = new Condition('');
            $jqlLoader = new JqlBasedLoader($sr, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($sr, $condition, $collection, $jqlLoader);
            $processor->process();
        } catch (JiraException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}