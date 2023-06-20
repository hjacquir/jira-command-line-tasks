<?php

declare(strict_types=1);

namespace App\Parser;

interface Parser
{
    public function parse(): mixed;
}
