<?php

namespace App\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Termwind\Components\Dd;

abstract class AbstractTransformer
{
    abstract public function includes(): array;

    abstract public function data(Model $model): array;

    public function addIncludes(Model $model, Collection $requestedIncludes): array
    {
        $data = [];

        foreach ($requestedIncludes as $key) {
            $after = Str::contains($key, '.') ? Str::after($key, '.') : null;
            $key = Str::before($key, '.');

            $c = $this->includes();

            if (isset($c[$key])) {
                $transformer = $c[$key];

                $relation = $model->$key;

                // todo: fix bug with duplication when nested duplicated keys
                if ($relation instanceof Model) {
                    $data[$key] = $this->addInclude($data, $key, $transformer, $relation, collect($after));
                } elseif ($relation instanceof Collection) {

                    foreach ($relation as $item) {
                        $data[$key][] = $this->addInclude($data, $key, $transformer, $item, collect($after));
                    }
                }
            }
        }

        return $data;
    }

    public function addInclude(array $data, string $key, string $transformer, Model $model, Collection $requestedIncludes): ?array
    {
        $transformer = app($transformer);

        return  $transformer->transform($model, $requestedIncludes);
    }

    public function transform(Model $model, Collection $requestedIncludes): array
    {
        $data = $this->data($model);

        $includes = $this->addIncludes($model, $requestedIncludes);

        return [...$data, ...$includes];
    }

    protected function getTimestamps(Model $model)
    {
        return [
            'updated_at' => $model->updated_at->format('Y-m-d H:i'),
            'created_at' => $model->created_at->format('Y-m-d H:i'),
        ];
    }
}
