<?php

declare(strict_types=1);

namespace App\Jql;

class Expression
{
    public function __construct(private string $content)
    {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): Expression
    {
        $this->content = $content;

        return $this;
    }
}
