<?php

namespace App\Http\Transformers;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductTransformer extends AbstractTransformer
{
    public function includes(): array
    {
        return [
            'categories' => CategoryTransformer::class,
        ];
    }

    public function data(Model|Product $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'price' => $model->price,
            ...$this->getTimestamps($model),
        ];
    }
}
