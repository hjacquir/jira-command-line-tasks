<?php

declare(strict_types=1);

namespace App\Validator\YamlFile\KeyValueValidator;

use App\Exception\YamlKeyNotDefinedException;
use App\Exception\YamlValueWrongFormatException;
use App\Validator\Validator;

abstract class KeyValueValidator implements Validator
{
    public function __construct(private string $yamlFilePath)
    {
    }

    protected function validKey(array $array, string $keyName)
    {
        if (false === isset($array[$keyName])) {
            throw new YamlKeyNotDefinedException("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The key '{$keyName}' is not defined. Please check your yaml file and define it.");
        }
    }

    protected function valueIsArray($value, string $key)
    {
        if (false === is_array($value)) {
            throw new YamlValueWrongFormatException("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The value for the key : '{$key}' must be an array. Please check your yaml file.");
        }
    }

    protected function valueIsString($value, string $key)
    {
        if (false === is_string($value)) {
            throw new YamlValueWrongFormatException("Wrong yaml file configuration in : '{$this->yamlFilePath}'. The value for the key : '{$key}' must be an string. Please check your yaml file.");
        }

    }

    public function valid($value)
    {
        $this->isValid($value);
    }

    protected abstract function isValid($value);
}
