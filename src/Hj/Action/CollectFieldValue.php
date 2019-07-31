<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class CollectFieldValue implements Action
{
    /**
     * @var array
     */
    private $fields;

    /**
     * @var array
     */
    private $collectedValues = [];

    /**
     * CollectFieldValue constructor.
     * @param $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param Issue $issue
     */
    public function apply(Issue $issue)
    {
        $values = [];

        foreach ($this->fields as $field) {
            array_push($values, $field->getValue($issue));
        }
        array_push($this->collectedValues, $values);
    }

    /**
     * @return array
     */
    public function getCollectedValues(): array
    {
        return $this->collectedValues;
    }
}