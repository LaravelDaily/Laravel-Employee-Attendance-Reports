<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyTimeEntryRequest;
use App\Http\Requests\StoreTimeEntryRequest;
use App\Http\Requests\UpdateTimeEntryRequest;
use App\TimeEntry;
use App\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimeEntriesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('time_entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timeEntries = TimeEntry::all();

        return view('admin.timeEntries.index', compact('timeEntries'));
    }

    public function create()
    {
        abort_if(Gate::denies('time_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.timeEntries.create', compact('users'));
    }

    public function store(StoreTimeEntryRequest $request)
    {
        $timeEntry = TimeEntry::create($request->all());

        return redirect()->route('admin.time-entries.index');
    }

    public function edit(TimeEntry $timeEntry)
    {
        abort_if(Gate::denies('time_entry_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::all()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $timeEntry->load('user');

        return view('admin.timeEntries.edit', compact('users', 'timeEntry'));
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry)
    {
        $timeEntry->update($request->all());

        return redirect()->route('admin.time-entries.index');
    }

    public function showCurrent()
    {
        $timeEntry = TimeEntry::whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        return response()->json(compact('timeEntry'));
    }

    public function updateCurrent()
    {
        $timeEntry = TimeEntry::whereNull('time_end')
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->first();

        if ($timeEntry)
        {
            $timeEntry->update([
                'time_end' => now()
            ]);

            return response()->json([
                'status' => 'Work time has stopped at [' . gmdate("H:i:s", $timeEntry->total_time) . '] hours'
            ]);
        } else {
            auth()->user()->timeEntries()->create([
                'time_start' => now()
            ]);

            return response()->json([
                'status' => 'Work time has started'
            ]);
        };
    }

    public function show(TimeEntry $timeEntry)
    {
        abort_if(Gate::denies('time_entry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timeEntry->load('user');

        return view('admin.timeEntries.show', compact('timeEntry'));
    }

    public function destroy(TimeEntry $timeEntry)
    {
        abort_if(Gate::denies('time_entry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timeEntry->delete();

        return back();
    }

    public function massDestroy(MassDestroyTimeEntryRequest $request)
    {
        TimeEntry::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
