<?php

namespace App\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class AbstractModelTransformer
{
    abstract public function includes(): array;

    abstract public function data(Model $model): array;

    public function transform(Model $model, ?Collection $requestedIncludes): array
    {
        $data = $this->data($model);

        $includes = $requestedIncludes ? $this->addIncludes($model, $requestedIncludes) : [];

        return [...$data, ...$includes];
    }

    private function addIncludes(Model $model, Collection $requestedIncludes): array
    {
        $nested = [];

        foreach ($requestedIncludes as $key) {
            $this->assignArrayByPath($nested, $key);
        }

        $result = [];

        foreach ($nested as $key => $children) {
            if ($transformerClass = $this->includes()[$key] ) {
                $transformer = app($transformerClass);

                if ($transformer instanceof AbstractModelTransformer) {
                    $relation = $model->$key ?? null;

                    $relationIncludes = $children ? collect(array_keys($children)) : null;

                    if ($relation instanceof Model) {
                        $result[$key] = $transformer->transform($relation, $relationIncludes);
                    } elseif ($relation instanceof Collection) {
                        foreach ($relation as $item) {
                            $result[$key][] = $transformer->transform($item, $relationIncludes);
                        }
                    }
                }
            }
        }

        return $result;
    }

    private function assignArrayByPath(array &$arr, string $path, string $separator = '.'): void
    {
        $keys = explode($separator, $path);

        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }
    }
}
