<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\AddComment;
use Hj\Action\ChangeAssignee;
use Hj\Condition\AlwaysTrue;
use Hj\Exception\EmptyCommentException;
use Hj\Exception\EmptyStringException;
use Hj\Jql\Condition;
use Hj\Jql\Jql;
use Hj\JqlConfigurator;
use Hj\Loader\JqlBasedLoader;
use Hj\Processor\Processor;
use JiraRestApi\Issue\Comment;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommentAndAssignCommand extends Command
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

        $this->setName('helper:comment-assign');
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

        $this->setHelp('Comment and assign. You can customize your comment into the file : comment.php');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $assigneeName = $input->getArgument('assignee');
        $loadedComment = '';
        include __DIR__ . '/../../../files/comment.php';
        $commentBody = $loadedComment;
        if ($loadedComment == '') {
            throw new EmptyStringException("The comment body can not be empty.");
        }
        try {
            $sr = new IssueService();
            $condition = new AlwaysTrue();
            $comment = new Comment();
            $comment->setBody($commentBody);
            $commentAction = new AddComment($sr, $comment, $this->logger);
            $assigneeAction = new ChangeAssignee($sr, $assigneeName, $this->logger);
            $collection = new ActionCollection();
            $collection->addAction($commentAction);
            $collection->addAction($assigneeAction);

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