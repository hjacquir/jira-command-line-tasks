<?php

declare(strict_types=1);

namespace App\File;

use App\Parser\Parser;

class JqlFile
{
    public const KEY_PROJECT = 'project';
    public const KEY_CONDITIONS = 'conditions';
    public const KEY_EXPRESSIONS = 'expressions';
    public const KEY_NAME = 'name';
    public const KEY_OPERATOR = 'operator';
    public const KEY_ISSUE_SUFFIX = 'issueSuffix';

    private array $parsedValues;

    public function __construct(private Parser $parser)
    {
        $this->parsedValues = $this->parser->parse();
    }

    public function getConditions(): array
    {
        return $this->parsedValues[self::KEY_CONDITIONS];
    }

    public function getExpressions() : array
    {
        return $this->parsedValues[self::KEY_EXPRESSIONS];
    }

    public function getName() : string
    {
        return $this->parsedValues[self::KEY_PROJECT][self::KEY_NAME];
    }

    public function getOperator() : string
    {
       return $this->parsedValues[self::KEY_PROJECT][self::KEY_OPERATOR];
    }

    public function getIssueSuffix() : string
    {
        return $this->parsedValues[self::KEY_PROJECT][self::KEY_ISSUE_SUFFIX];
    }
}
