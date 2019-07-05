<?php
/**
 * Created by PhpStorm.
 * User: monpc
 * Date: 16/03/2019
 * Time: 15:44
 */

namespace Hj\Collector;


interface Collector {

    public function collect(array $values);

    public function getCollectedValues();

}