<?php

declare(strict_types=1);

namespace App\Parser;

use App\JqlConfiguration;
use App\Validator\Validator;
use Symfony\Component\Config\Definition\Processor;
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
        $config = Yaml::parseFile($this->yamlFile);
        $processor = new Processor();
        $jqlConfiguration = new JqlConfiguration();

        $processConfiguration = $processor->processConfiguration(
            $jqlConfiguration,
            [$config]
        );

        return $processConfiguration;
    }
}
