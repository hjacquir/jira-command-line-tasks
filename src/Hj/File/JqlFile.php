<?php

namespace Hj\File;

use Hj\Parser\Parser;

class JqlFile
{
    const KEY_PROJECT = 'project';
    const KEY_CONDITIONS = 'conditions';
    const KEY_EXPRESSIONS = 'expressions';
    const KEY_NAME = 'name';
    const KEY_OPERATOR = 'operator';
    const KEY_ISSUE_SUFFIX = 'issueSuffix';

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $parsedValues;

    /**
     * JqlFile constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        $this->parsedValues = $this->parser->parse();
    }

    /**
     * @return array
     */
    public function getConditions(): array
    {
        return $this->parsedValues[self::KEY_CONDITIONS];
    }

    /**
     * @return array
     */
    public function getExpressions() : array
    {
        return $this->parsedValues[self::KEY_EXPRESSIONS];
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->parsedValues[self::KEY_PROJECT][self::KEY_NAME];
    }

    /**
     * @return string
     */
    public function getOperator() : string
    {
       return $this->parsedValues[self::KEY_PROJECT][self::KEY_OPERATOR];
    }

    /**
     * @return string
     */
    public function getIssueSuffix() : string
    {
        return $this->parsedValues[self::KEY_PROJECT][self::KEY_ISSUE_SUFFIX];
    }
}