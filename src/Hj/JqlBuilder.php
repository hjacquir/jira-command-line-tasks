<?php

namespace Hj;

use Hj\File\JqlFile;
use Hj\Jql\Condition;
use Hj\Jql\Expression;
use Hj\Jql\Jql;
use Hj\Jql\Project;
use Symfony\Component\Yaml\Yaml;

class JqlBuilder
{
    /**
     * @var Jql
     */
    private $jql;

    /**
     * @var JqlFile
     */
    private $jqlFile;

    /**
     * JqlBuilder constructor.
     * @param Jql $jql
     * @param JqlFile $jqlFile
     */
    public function __construct(Jql $jql, JqlFile $jqlFile)
    {
        $this->jql = $jql;
        $this->jqlFile = $jqlFile;
    }

    /**
     * @return Jql
     */
    public function build() : Jql
    {
        $this->jql->setProject(
            new Project(
                $this->jqlFile->getName(),
                $this->jqlFile->getOperator(),
                $this->jqlFile->getIssueSuffix()
            )
        );

        $conditions = $this->jqlFile->getConditions();

        foreach ($conditions as $condition) {
            $this->jql->addConditions(new Condition($condition));
        }

        $expressions = $this->jqlFile->getExpressions();

        foreach ($expressions as $expression) {
            $this->jql->addExpression(new Expression($expression));
        }

        return $this->jql;
    }
}