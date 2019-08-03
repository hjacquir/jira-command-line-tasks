<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class CollectFieldValue implements Action
{
    /**
     * @var array
     */
    private $fieldValues;

    /**
     * @var array
     */
    private $collectedValues = [];

    /**
     * CollectFieldValue constructor.
     * @param $fieldValues
     */
    public function __construct(array $fieldValues)
    {
        $this->fieldValues = $fieldValues;
    }

    /**
     * @param Issue $issue
     */
    public function apply(Issue $issue)
    {
        $values = [];

        foreach ($this->fieldValues as $fieldValue) {
            array_push($values, $fieldValue->getValue($issue));
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