<?php

namespace Hj;

use Hj\Jql\Condition;
use Hj\Jql\Expression;
use Hj\Jql\Jql;
use Hj\Jql\Project;
use Symfony\Component\Yaml\Yaml;

class JqlConfigurator
{
    /**
     * @var Jql
     */
    private $jql;

    public function __construct(Jql $jql)
    {
        $this->jql = $jql;
    }

    public function configure($yamlFile)
    {
        $value = Yaml::parseFile($yamlFile);
        $this->jql->setProject(
            new Project(
                $value['project']['name'],
                $value['project']['operator'],
                $value['project']['issueSuffix']
            )
        );

        $conditions = $value['conditions'];

        foreach ($conditions as $condition) {
            $this->jql->addConditions(new Condition($condition));
        }

        $expressions = $value['expressions'];

        foreach ($expressions as $expression) {
            $this->jql->addExpression(new Expression($expression));
        }

        return $this->jql;
    }
}