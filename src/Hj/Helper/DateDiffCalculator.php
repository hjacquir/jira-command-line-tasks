<?php

namespace Hj\Helper;

class DateDiffCalculator
{
    const FORMAT_DAY = '%R%a';

    /**
     * @param \DateTime $firstDateTime
     * @param \DateTime $secondDatetime
     *
     * @return string The number of days
     */
    public function calculate(\DateTime $firstDateTime, \DateTime $secondDatetime)
    {
        return $firstDateTime->diff($secondDatetime)->format(self::FORMAT_DAY);
    }

}