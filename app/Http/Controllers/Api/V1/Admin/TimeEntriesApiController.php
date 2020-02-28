<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimeEntryRequest;
use App\Http\Requests\UpdateTimeEntryRequest;
use App\Http\Resources\Admin\TimeEntryResource;
use App\TimeEntry;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TimeEntriesApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('time_entry_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TimeEntryResource(TimeEntry::with(['user'])->get());
    }

    public function store(StoreTimeEntryRequest $request)
    {
        $timeEntry = TimeEntry::create($request->all());

        return (new TimeEntryResource($timeEntry))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(TimeEntry $timeEntry)
    {
        abort_if(Gate::denies('time_entry_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new TimeEntryResource($timeEntry->load(['user']));
    }

    public function update(UpdateTimeEntryRequest $request, TimeEntry $timeEntry)
    {
        $timeEntry->update($request->all());

        return (new TimeEntryResource($timeEntry))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(TimeEntry $timeEntry)
    {
        abort_if(Gate::denies('time_entry_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $timeEntry->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
