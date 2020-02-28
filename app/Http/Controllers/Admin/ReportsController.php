<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use App\User;
use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class ReportsController extends Controller
{
    public function index(Request $request, ReportService $reportService)
    {
        $employees = User::whereHas('roles', function ($query) {
                $query->whereId(2);
            })
            ->get();
        $dateRange = $reportService->generateDateRange();

        $timeEntries = $reportService->generateReport($request->input('employee'));
        if ($timeEntries) {
            $chart = new LaravelChart([
                'chart_title'           => 'Hours of work per day',
                'chart_type'            => 'line',
                'report_type'           => 'group_by_date',
                'model'                 => 'App\\TimeEntry',
                'group_by_field'        => 'time_start',
                'group_by_period'       => 'day',
                'aggregate_function'    => 'sum',
                'aggregate_field'       => 'total_time_chart',
                'column_class'          => 'col-md-8',
                'continuous_time'       => true,
            ]);
        } else {
            $chart = NULL;
        }

        return view('admin.reports.index', compact('timeEntries', 'employees', 'dateRange', 'chart'));
    }
}
