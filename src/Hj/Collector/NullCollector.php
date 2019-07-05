<?php
/**
 * Created by PhpStorm.
 * User: monpc
 * Date: 16/03/2019
 * Time: 15:45
 */

namespace Hj\Collector;


class NullCollector implements Collector
{

    public function collect(array $values)
    {
        // TODO: Implement collect() method.
    }

    public function getCollectedValues()
    {
        return null;
    }
}