<?php

namespace App\Http\Transformers;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;

class CategoryTransformer extends AbstractTransformer
{
    public function includes(): array
    {
        return [
            'parent' => CategoryTransformer::class,
            'children' => CategoryTransformer::class,
            'products' => ProductTransformer::class,
        ];
    }

    public function data(Model|ProductCategory $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            ...$this->getTimestamps($model),
        ];
    }
}
