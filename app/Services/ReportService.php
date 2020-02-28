<?php

namespace App\Services;

use App\TimeEntry;
use Carbon\CarbonPeriod;

class ReportService
{
    public function generateReport($employee)
    {
        $user = auth()->user();
        $dateRange = [
            now()->subDays(config('app.reports.range'))->setTime(0, 0, 0),
            now()->addDay()->setTime(0, 0, 0)
        ];

        return TimeEntry::whereNotNull('time_end')
            ->whereBetween('time_start', $dateRange)
            ->get()
            ->groupBy('date_start')
            ->map(function ($items) {
                $sum = 0;

                foreach($items as $item)
                {
                    $sum += $item->total_time;
                }

                return $sum;
            })
            ->toArray();
    }

    public function generateDateRange()
    {
        $period = CarbonPeriod::create(now()->subDays(config('app.reports.range')), now());
        $dateRange = [];

        foreach ($period as $date) {
            array_unshift($dateRange, $date->format('Y-m-d'));
        }

        return $dateRange;
    }
}
