<?php

namespace Hj\Validator\YamlFile\KeyValueValidator;

use Hj\Exception\YamlKeyNotDefined;
use Hj\Exception\YamlValueWrongFormat;
use Hj\Validator\Validator;

abstract class KeyValueValidator implements Validator
{
    /**
     * @var string
     */
    private $yamlFilePath;

    /**
     * KeyValueValidator constructor.
     * @param string $yamlFilePath
     */
    public function __construct(string $yamlFilePath)
    {
        $this->yamlFilePath = $yamlFilePath;
    }


    /**
     * @param array $array
     * @param string $keyName
     * @throws YamlKeyNotDefined
     */
    protected function validKey(array $array, string $keyName)
    {
        if (!isset($array[$keyName])) {
            throw new YamlKeyNotDefined("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The key '{$keyName}' is not defined. Please check your yaml file and define it.");
        }
    }

    /**
     * @param $value
     * @param string $key
     * @throws YamlValueWrongFormat
     */
    protected function valueIsArray($value, string $key)
    {
        if (!is_array($value)) {
            throw new YamlValueWrongFormat("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The value for the key : '{$key}' must be an array. Please check your yaml file.");
        }
    }

    protected function valueIsString($value, string $key)
    {
        if (!is_string($value)) {
            throw new YamlValueWrongFormat("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The value for the key : '{$key}' must be an string. Please check your yaml file.");
        }

    }

    public function valid($value)
    {
        $this->isValid($value);
    }

    protected abstract function isValid($value);
}