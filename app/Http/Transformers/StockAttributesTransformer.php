<?php

namespace App\Http\Transformers;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;

class StockAttributesTransformer extends AbstractModelTransformer
{
    public function includes(): array
    {
        // todo
        return [];
    }

    public function data(Model|ProductAttribute $model): array
    {
        return [
            'id' => $model->id,
            'name' => $model->attribute->name,
            'value' => $model->value->value,
        ];
    }
} {
}
