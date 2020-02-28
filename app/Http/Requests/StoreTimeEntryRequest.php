<?php

namespace App\Http\Requests;

use App\TimeEntry;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreTimeEntryRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('time_entry_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'user_id'    => [
                'required',
                'integer'],
            'time_start' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format')],
            'time_end'   => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable'],
        ];
    }
}
