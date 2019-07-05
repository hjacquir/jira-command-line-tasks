<?php

namespace Hj\Helper;

use Symfony\Component\Yaml\Yaml;

/**
 * Get date from yaml config file
 *
 * Class DateGetter
 * @package Hj\Helper
 */
class DateGetter
{
    /**
     * @var string
     */
    private $yamlFile;

    /**
     * @var array
     */
    private $value;

    /**
     * @param $yamlFile
     */
    public function __construct($yamlFile)
    {
        $this->yamlFile = $yamlFile;
        $this->value = Yaml::parseFile($this->yamlFile);
    }

    public function getWeek()
    {
        return $this->value['date']['week'];
    }

    public function getYear()
    {
        return $this->value['date']['year'];
    }
}