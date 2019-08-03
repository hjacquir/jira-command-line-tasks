<?php

namespace Hj\FieldValue\Date\Created;

use Hj\FieldValue\FieldValue;
use JiraRestApi\Issue\Issue;

class StringValue implements FieldValue
{
    /**
     * @var string
     */
    private $format;

    /**
     * StringValue constructor.
     * @param string $format
     */
    public function __construct(string $format)
    {
        $this->format = $format;
    }

    /**
     * @param Issue $issue
     * @return string
     */
    public function getValue(Issue $issue) : string
    {
        return $issue->fields->created->format($this->format);
    }
}