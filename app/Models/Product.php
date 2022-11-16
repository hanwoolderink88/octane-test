<?php

namespace App\Models;

use App\Models\Pivot\ProductCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $this
            ->belongsToMany(Category::class, 'product_category')
            ->using(ProductCategory::class);
    }

    public function attributeValues(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
