@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Reports
    </div>

    <div class="card-body">
        @if(auth()->user()->is_admin)
            <form>
                <div class="form-group">
                    <label class="required" for="employee">Employee</label>
                    <select class="form-control" name="employee" id="employee">
                        <option hidden>Select an employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ request()->input('employee') == $employee->id ? 'selected' : '' }}>{{ $employee->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif
        @if ((!auth()->user()->is_admin || request()->input('employee')) && ($timeEntries))
            <div class="row">
                <div class="{{ $chart->options['column_class'] }}">
                    <h3>{!! $chart->options['chart_title'] !!}</h3>
                    {!! $chart->renderHtml() !!}
                </div>

                <div class="col-md-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        Day
                                    </th>
                                    <th>
                                        Total time
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dateRange as $date)
                                    <tr>
                                        <td>
                                            {{ $date }}
                                        </td>
                                        <td>
                                            {{ gmdate("H:i:s", $timeEntries[$date] ?? 0) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <p class="text-center">Select an employee to view his report</p>
        @endif
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
@if ($chart)
{!! $chart->renderJs() !!}
@endif
<script>
$(function () {
    $('#employee').change(function () {
        $(this).parents('form').submit();
    });
});
</script>
@endsection
