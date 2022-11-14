<?php

namespace App\Http\Response;

use App\Http\Transformers\AbstractModelTransformer;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Response
{
    public function index(
        Builder|QueryBuilder      $builder,
        ?AbstractModelTransformer $transformer = null,
        array                     $columns = ['*'],
    ): JsonResponse
    {
        $perPage = min(200, request()->query('per_page', 20));

        if ($transformer) {
            $includes = $this->getIncludes();

            $response = $builder->simplePaginate($perPage)->through(fn($model, $key) => $transformer->transform($model, $includes));
        } else {
            $response = $builder->simplePaginate($perPage, $columns);
        }

        return new JsonResponse($response, 200);
    }

    public function model(Model $model, ?AbstractModelTransformer $transformer): JsonResponse
    {
        if ($transformer) {
            $model = $transformer->transform($model, $this->getIncludes());
        }

        return new JsonResponse($model, 200);
    }

    private function getIncludes(): Collection
    {
        $include = request()->query('include');

        return $include ? Str::of($include)->explode(',') : collect();
    }
}
