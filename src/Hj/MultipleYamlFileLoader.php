<?php

namespace Hj;

use Symfony\Component\Yaml\Yaml;

class MultipleYamlFileLoader
{
    /**
     * @param string $yamlFile
     * @return array
     */
    public function load(string $yamlFile) : array
    {
        $value = Yaml::parseFile($yamlFile);
        return $value['files'];
    }

}