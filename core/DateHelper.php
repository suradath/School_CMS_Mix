<?php
declare(strict_types=1);

namespace Core;

use DateTime;
use DateInterval;
use DatePeriod;

class DateHelper
{
    /**
     * Count working days (Mon-Fri) between two dates inclusive
     */
    public static function countWorkingDays(string $startDate, string $endDate): float
    {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); // Inclusive

        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($start, $interval, $end);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), ['6', '7'])) {
                $days++;
            }
        }

        return (float)$days;
    }

    /**
     * Check if two date ranges overlap
     */
    public static function isOverlapping(string $start1, string $end1, string $start2, string $end2): bool
    {
        return (strtotime($start1) <= strtotime($end2)) && (strtotime($end1) >= strtotime($start2));
    }
}
