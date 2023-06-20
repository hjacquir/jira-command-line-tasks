<?php

declare(strict_types=1);

namespace App\Parser;

use App\Validator\Validator;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements Parser
{
    public function __construct(
        private string $yamlFile,
        private Validator $validator
    ) {
    }

    public function parse(): mixed
    {
        $value = Yaml::parseFile($this->yamlFile);
        $this->validator->valid($value);

        return $value;
    }
}
