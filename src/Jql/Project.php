<?php

declare(strict_types=1);

namespace App\Jql;

class Project
{
    public function __construct(
        private string $name,
        private string $operator,
        private string $issueSuffix
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
    //@todo deprecated remove
    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getIssueSuffix(): string
    {
        return $this->issueSuffix;
    }
}
