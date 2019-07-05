<?php

namespace Hj\Helper;

use DateTime;

class StartEndDateWeekGetter
{
    const DATE_FORMAT = 'Y-m-d';
    const INTERVALL_DAYS = '+6 days';

    private $currentDate;

    private $week;

    private $year;

    private $startDate;

    private $endDate;

    public function __construct($week, $year)
    {
        $this->currentDate = new DateTime();
        $this->week = $week;
        $this->year = $year;
        $this->currentDate->setISODate($this->year, $this->week);
        $this->startDate = $this->currentDate->format(self::DATE_FORMAT);
        $this->currentDate->modify(self::INTERVALL_DAYS);
        $this->endDate = $this->currentDate->format(self::DATE_FORMAT);
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return DateTime
     */
    public function getCurrentDate()
    {
        return $this->currentDate;
    }
}