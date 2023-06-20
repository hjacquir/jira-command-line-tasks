<?php

declare(strict_types=1);

namespace App;

use App\File\JqlFile;
use App\Jql\Condition;
use App\Jql\Expression;
use App\Jql\Jql;
use App\Jql\Project;
use Symfony\Component\Yaml\Yaml;

class JqlBuilder
{
    public function __construct(private Jql $jql, private JqlFile $jqlFile)
    {
    }

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
