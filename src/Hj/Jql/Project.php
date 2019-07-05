<?php

namespace Hj\Jql;

class Project
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $operator;
    /**
     * @var string
     */
    private $issueSuffix;

    /**
     * @param string $name
     * @param string $operator
     * @param string $issueSuffix
     */
    function __construct($name, $operator, $issueSuffix)
    {
        $this->name = $name;
        $this->operator = $operator;
        $this->issueSuffix = $issueSuffix;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param mixed $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @return mixed
     */
    public function getIssueSuffix()
    {
        return $this->issueSuffix;
    }

    /**
     * @param mixed $issueSuffix
     */
    public function setIssueSuffix($issueSuffix)
    {
        $this->issueSuffix = $issueSuffix;
    }
}