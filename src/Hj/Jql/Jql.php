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
     * @var array
     */
    private $ticketsId;

    /**
     * @param $ticketsId
     */
    public function __construct($ticketsId)
    {
        $this->ticketsId = $ticketsId;
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return Condition[]
     */
    public function getConditions()
    {
        return $this->conditions;
    }

    /**
     * @param Condition[] $conditions
     */
    public function setConditions($conditions)
    {
        $this->conditions = $conditions;
    }

    /**
     * @return Expression[]
     */
    public function getExpressions()
    {
        return $this->expressions;
    }

    /**
     * @param Expression[] $expressions
     */
    public function setExpressions($expressions)
    {
        $this->expressions = $expressions;
    }

    /**
     * @return array
     */
    public function getTicketsId()
    {
        return $this->ticketsId;
    }

    /**
     * @param array $ticketsId
     */
    public function setTicketsId($ticketsId)
    {
        $this->ticketsId = $ticketsId;
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

    public function removeCondition(Condition $condition)
    {
        $key = array_search($condition, $this->conditions);

        if (false !== $key) {
            array_splice($this->conditions, $key, 1);
        }
    }

    public function __toString()
    {
        $string = 'project ' . $this->project->getOperator() . ' "' . $this->project->getName() . '"';

        foreach ($this->conditions as $condition) {
            $string = $string . ' ' . $condition->getContent();
        }
        if (!empty($this->ticketsId)) {
            $string = $string . ' AND issueKey in (';
            $issueKey = '';
            foreach ($this->ticketsId as $range) {
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


}