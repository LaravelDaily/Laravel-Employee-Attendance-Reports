<?php

use App\Role;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TimeEntriesTableSeeder extends Seeder
{
    public function run(ReportService $reportService)
    {
        $users = Role::findOrFail(2)->users;
        $dateRange = $reportService->generateDateRange();

        foreach($users as $user)
        {
            $currentDateRange = array_merge($dateRange);
            shuffle($currentDateRange);

            for($i = 0; $i < 10; $i++)
            {
                $date = Carbon::parse(array_pop($currentDateRange))->setTime(0, 0, 0);
                $startTime = rand(28800, 36000);
                $endTime = rand($startTime + 7200, 64800);

                $user->timeEntries()->create([
                    'time_start' => (clone $date)->addSeconds($startTime),
                    'time_end'   => (clone $date)->addSeconds($endTime),
                ]);
            }
        }
    }
}
