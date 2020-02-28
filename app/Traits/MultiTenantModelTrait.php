<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $isAdmin = auth()->user()->roles->contains(1);
            static::creating(function ($model) {
                $model->user_id = auth()->id();
            });

            static::addGlobalScope('user_id', function (Builder $builder) use ($isAdmin) {
                $builder->when($isAdmin && request()->route()->named('admin.reports.index'), function ($query) {
                        $query->where('user_id', request()->input('employee'));
                    })
                    ->when(!$isAdmin, function ($query) {
                        $query->where('user_id', auth()->id());
                    });
            });
        }
    }
}
