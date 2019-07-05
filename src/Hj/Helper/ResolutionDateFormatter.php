<?php

namespace Hj\Helper;

/**
 * Format resolution date
 *
 * Class ResolutionDateFormatter
 * @package Hj\Helper
 */
class ResolutionDateFormatter
{
    /**
     * @param $resolutionDateAsString
     * @return \DateTime
     * @throws \Exception
     */
    public function getResolutionDateAsDateTime($resolutionDateAsString)
    {
        $yearMonthString = substr($resolutionDateAsString, 0, 10);
        $dayAsString = substr($resolutionDateAsString, 11, 8);
        $dateAsStringFormatted = $yearMonthString . ' ' .$dayAsString;

        return new \DateTime($dateAsStringFormatted);
    }
}