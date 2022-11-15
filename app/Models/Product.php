<?php

namespace App\Models;

use App\Models\Pivot\ProductAttribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property float $price
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Collection<int, Product> $products
 */
class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $casts = [
        'price' => 'float',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function attributeValues(): BelongsToMany
    {
        return $this
            ->belongsToMany(AttributeValue::class, 'product_attribute_value')
            ->using(ProductAttribute::class);
    }
}
