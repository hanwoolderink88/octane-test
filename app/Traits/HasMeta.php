<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasMeta
{
    protected function applyMeta(Builder $builder): void
    {
        $this->applyInclude($builder);
    }

    protected function applyInclude(Builder $builder): void
    {
        // todo: how to get the request data in here?
        $includes = Str::of(request()->query('include', ''))->explode(',');

        $includes->filter()->each(function ($include) use ($builder) {
            $builder->with(Str::of($include)->trim()->__toString());
        });
    }

    protected function applyIncludeToModel(Model $model): void
    {
        // todo: how to get the request data in here?
        $includes = Str::of(request()->query('include', ''))->explode(',');

        $includes->filter()->each(function ($include) use ($model) {
            $model->load(Str::of($include)->trim()->__toString());
        });
    }
}
