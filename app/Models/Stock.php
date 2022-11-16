<?php

namespace App\Models;

use App\Models\Pivot\StockAttribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stock extends Model
{
    protected $table = 'stock';

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                ProductAttribute::class,
                'stock_product_attribute_value',
                'stock_id',
                'product_attribute_value_id',
            )
            ->using(StockAttribute::class);
    }
}
