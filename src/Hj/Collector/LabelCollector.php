<?php

namespace Hj\Collector;

class LabelCollector implements Collector
{
    /**
     * @var array
     */
    private $collectedValues = [
        'TOTAL' => 0,
    ];

    public function collect(array $labels) {
        foreach ($labels as $label) {
            if (array_key_exists($label, $this->collectedValues)) {
                $occurence = $this->collectedValues[$label];
                $this->collectedValues[$label] = $occurence + 1;
            } else {
                $this->collectedValues[$label] = 1;
            }
        }
    }

    /**
     * @return array
     */
    public function getCollectedValues()
    {
        foreach ($this->collectedValues as $value) {
            $total = $this->collectedValues['TOTAL'];
            $total = $total + $value;
           $this->collectedValues['TOTAL'] = $total;
        }

        return $this->collectedValues;
    }
}