<?php

namespace Hj;

use Symfony\Component\Yaml\Yaml;

class MultipleYamlFileLoader
{
    /**
     * @return array
     */
    public function load($yamlFile)
    {
        $value = Yaml::parseFile($yamlFile);
        return $value['files'];
    }

}