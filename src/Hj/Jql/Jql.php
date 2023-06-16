<?php

declare(strict_types=1);

namespace Hj\Jql;

class Jql
{
    private Project $project;

    /** @var Condition[] */
    private array $conditions = [];

    /** @var Expression[] */
    private array $expressions = [];

    public function __construct(private string $issueIdsAsString)
    {
    }

    public function setProject(Project $project)
    {
        $this->project = $project;
    }

    public function addConditions(Condition $condition)
    {
        if (false === array_search($condition, $this->conditions)) {
            array_push($this->conditions, $condition);
        }
    }

    public function addExpression(Expression $expression)
    {
        if (false === array_search($expression, $this->expressions)) {
            array_push($this->expressions, $expression);
        }
    }

    public function __toString(): string
    {
        $string = 'project ' . $this->project->getOperator() . ' "' . $this->project->getName() . '"';

        foreach ($this->conditions as $condition) {
            $string = $string . ' ' . $condition->getContent();
        }
        if (!empty($this->issueIdsAsString)) {
            $string = $string . ' AND issueKey in (';
            $issueKey = '';
            $issueIdsAsArray = $this->issueIdsToArray($this->issueIdsAsString, ',');
            foreach ($issueIdsAsArray as $range) {
                $issueKey = $issueKey . $this->project->getIssueSuffix() . '-' . $range . ',';
            }
            // remove the last comma
            $issueKey = substr($issueKey, 0, -1);
            // add the parenthesis
            $issueKey = $issueKey . ')';
            $string = $string . $issueKey;
        }

        foreach ($this->expressions as $expression) {
            $string = $string . ' ' . $expression->getContent();
        }

        return $string;
    }

    public function issueIdsToArray(string $issuesIdsAsString, $delimiter): array
    {
        return explode($delimiter, $issuesIdsAsString);
    }
}
