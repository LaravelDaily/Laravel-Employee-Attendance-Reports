@extends('layouts.admin')
@section('content')
@can('time_entry_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.time-entries.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.timeEntry.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.timeEntry.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-TimeEntry">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.timeEntry.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.timeEntry.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.timeEntry.fields.time_start') }}
                        </th>
                        <th>
                            {{ trans('cruds.timeEntry.fields.time_end') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($timeEntries as $key => $timeEntry)
                        <tr data-entry-id="{{ $timeEntry->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $timeEntry->id ?? '' }}
                            </td>
                            <td>
                                {{ $timeEntry->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $timeEntry->time_start ?? '' }}
                            </td>
                            <td>
                                {{ $timeEntry->time_end ?? '' }}
                            </td>
                            <td>
                                @can('time_entry_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.time-entries.show', $timeEntry->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('time_entry_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.time-entries.edit', $timeEntry->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('time_entry_delete')
                                    <form action="{{ route('admin.time-entries.destroy', $timeEntry->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('time_entry_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.time-entries.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-TimeEntry:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection