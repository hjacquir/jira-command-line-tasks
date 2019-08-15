<?php

namespace Hj\Parser;

use Hj\Validator\Validator;
use Symfony\Component\Yaml\Yaml;

class YamlParser implements Parser
{
    /**
     * @var string
     */
    private $yamlFile;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * YamlParser constructor.
     * @param string $yamlFile
     * @param Validator $validator
     */
    public function __construct(string $yamlFile, Validator $validator)
    {
        $this->yamlFile = $yamlFile;
        $this->validator = $validator;
    }

    public function parse()
    {
        $value = Yaml::parseFile($this->yamlFile);
        $this->validator->valid($value);

        return $value;
    }
}