<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Termwind\Components\Dd;

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
}
