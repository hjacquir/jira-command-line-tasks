<?php

namespace Hj\Action;

use JiraRestApi\Issue\Issue;

class CountOptionsFieldSelected implements Action
{
    /**
     * @var array
     */
    public $arrayOfChoices;

    /**
     * @var string
     */
    public $customFieldName;

    /**
     * CountOptionsFieldSelected constructor.
     * @param string $customFieldName
     */
    public function __construct($customFieldName, $arrayOfChoices)
    {
        $this->customFieldName = $customFieldName;
        $this->arrayOfChoices = $arrayOfChoices;
    }

    /**
     * @return array
     */
    public function getArrayOfChoices()
    {
        return $this->arrayOfChoices;
    }

    /**
     * @param Issue $issue
     */
    public function apply(Issue $issue)
    {
        $field = $issue->fields;

        $selectedOption = null;

        if (isset($field->customFields[$this->customFieldName])) {
            $selectedOption = $field->customFields[$this->customFieldName]->value;
        }

        if (array_key_exists($selectedOption, $this->arrayOfChoices)) {
            $this->arrayOfChoices[$selectedOption] = $this->arrayOfChoices[$selectedOption] + 1;
        }
    }
}