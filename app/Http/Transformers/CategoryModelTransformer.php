<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Traits\HasTimestamps;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryModelTransformer extends AbstractModelTransformer
{
    use HasTimestamps;

    public function includes(): array
    {
        return [
            'parent' => CategoryModelTransformer::class,
            'children' => CategoryModelTransformer::class,
            'products' => ProductModelTransformer::class,
        ];
    }

    public function data(Model|Category $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            ...$this->getTimestamps($model),
        ];
    }
}
