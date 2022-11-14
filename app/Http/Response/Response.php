<?php

namespace App\Http\Response;

use App\Http\Transformers\AbstractTransformer;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Response
{
    public function index(Builder|QueryBuilder $builder, ?AbstractTransformer $transformer): JsonResponse
    {
        $perPage = request()->query('per_page', 20);

        $builder = $builder->paginate($perPage);

        if ($transformer) {
            $builder->through(fn ($model, $key) => $transformer->transform($model, $this->getIncludes()));
        }

        return new JsonResponse($builder, 200);
    }

    public function model(Model $model, ?AbstractTransformer $transformer): JsonResponse
    {
        if ($transformer) {
            $model = $transformer->transform($model, $this->getIncludes());
        }

        return new JsonResponse($model, 200);
    }

    private function getIncludes(): Collection
    {
        return Str::of(request()->query('include', ''))->explode(',');
    }
}
