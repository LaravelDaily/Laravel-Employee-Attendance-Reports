@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.timeEntry.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.time-entries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.timeEntry.fields.id') }}
                        </th>
                        <td>
                            {{ $timeEntry->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timeEntry.fields.user') }}
                        </th>
                        <td>
                            {{ $timeEntry->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timeEntry.fields.time_start') }}
                        </th>
                        <td>
                            {{ $timeEntry->time_start }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.timeEntry.fields.time_end') }}
                        </th>
                        <td>
                            {{ $timeEntry->time_end }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.time-entries.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection