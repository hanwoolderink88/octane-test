<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $product_id
 * @property int $attribute_id
 * @property int $attribute_value_id
 * @property Product $product
 * @property AttributeValue $value
 * @property Attribute $attribute
 */
class ProductAttribute extends Model
{
    protected $table = 'product_attribute_value';

    public $timestamps = false;

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->attribute_id = $model->value->attribute->id;
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(AttributeValue::class, 'attribute_value_id');
    }
}
