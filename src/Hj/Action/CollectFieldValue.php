<?php

declare(strict_types=1);

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class CollectFieldValue implements Action
{
    private array $fieldValues;

    private array $collectedValues = [];

    public function __construct(array $fieldValues)
    {
        $this->fieldValues = $fieldValues;
    }

    public function apply(Issue $issue)
    {
        $values = [];

        foreach ($this->fieldValues as $fieldValue) {
            array_push($values, $fieldValue->getValue($issue));
        }

        array_push($this->collectedValues, $values);
    }

    public function getCollectedValues(): array
    {
        return $this->collectedValues;
    }
}
