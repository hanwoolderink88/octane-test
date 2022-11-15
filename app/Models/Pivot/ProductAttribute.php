<?php

namespace App\Models\Pivot;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $attribute_id
 * @property int $attribute_value_id
 * @property Product $product
 * @property AttributeValue $value
 * @property Attribute $attribute
 */
class ProductAttribute extends Pivot
{
    protected $table = 'product_attribute_value';

    protected static function booted(): void
    {
        static::creating(function (self $pivot) {
            $pivot->attribute_id = $pivot->value->attribute->id;
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_value_id');
    }
}
