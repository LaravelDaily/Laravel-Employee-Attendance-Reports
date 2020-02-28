<?php

namespace App;

use App\Traits\MultiTenantModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeEntry extends Model
{
    use SoftDeletes, MultiTenantModelTrait;

    public $table = 'time_entries';

    protected $dates = [
        'time_end',
        'time_start',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'time_end',
        'time_start',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTimeStartAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setTimeStartAttribute($value)
    {
        $this->attributes['time_start'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getTimeEndAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setTimeEndAttribute($value)
    {
        $this->attributes['time_end'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getDateStartAttribute()
    {
        return $this->time_start ? Carbon::createFromFormat('Y-m-d H:i:s', $this->time_start)->format(config('panel.date_format')) : null;
    }

    public function getTotalTimeAttribute()
    {
        $time_start = $this->time_start ? Carbon::createFromFormat('Y-m-d H:i:s', $this->time_start) : null;
        $time_end = $this->time_end ? Carbon::createFromFormat('Y-m-d H:i:s', $this->time_end) : null;

        return $this->time_end ? $time_end->diffInSeconds($time_start) : 0;
    }

    public function getTotalTimeChartAttribute()
    {
        return $this->total_time ? round($this->total_time/3600, 2) : 0;
    }
}
