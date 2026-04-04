<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Models\School;
use Illuminate\Support\Facades\Auth;

trait HasSchoolScope
{
    public static function bootHasSchoolScope()
    {
        static::creating(function ($model) {
            if (empty($model->school_id) && Auth::check() && Auth::user()->school_id) {
                $model->school_id = Auth::user()->school_id;
            }
        });

        static::addGlobalScope('school', function (Builder $builder) {
            if (Auth::check() && Auth::user()->school_id) {
                $builder->where('school_id', Auth::user()->school_id);
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
