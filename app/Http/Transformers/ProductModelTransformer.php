<?php

namespace App\Http\Transformers;

use App\Http\Transformers\Traits\HasTimestamps;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductModelTransformer extends AbstractModelTransformer
{
    use HasTimestamps;

    public function includes(): array
    {
        return [
            'categories' => CategoryModelTransformer::class,
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
