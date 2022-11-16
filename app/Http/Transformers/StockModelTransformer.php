<?php

namespace App\Http\Transformers;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Model;

class StockModelTransformer extends AbstractModelTransformer
{
    public function includes(): array
    {
        return [
            'product' => ProductModelTransformer::class,
            'attributeValues' => StockAttributesTransformer::class,
        ];
    }

    public function data(Model|Stock $model): array
    {
        return [
            'id' => $model->id,
            'quantity' => $model->quantity,
        ];
    }
}
