<?php

namespace Hj\Jql;

class Jql
{

    /**
     * @var Project
     */
    private $project;

    /**
     * @var Condition[]
     */
    private $conditions = [];

    /**
     * @var Expression[]
     */
    private $expressions = [];

    /**
     * @var string
     */
    private $issueIdsAsString;

    /**
     * @param string $issueIdsAsString
     */
    public function __construct(string $issueIdsAsString)
    {
        $this->issueIdsAsString = $issueIdsAsString;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
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

    public function __toString()
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
            // on supprime la dernière virgule
            $issueKey = substr($issueKey, 0, -1);
            // on ajoute la parenthèse
            $issueKey = $issueKey . ')';
            $string = $string . $issueKey;
        }

        foreach ($this->expressions as $expression) {
            $string = $string . ' ' . $expression->getContent();
        }

        return $string;
    }

    public function issueIdsToArray(string $issuesIdsAsString, $delimiter)
    {
        return explode($delimiter, $issuesIdsAsString);
    }
}