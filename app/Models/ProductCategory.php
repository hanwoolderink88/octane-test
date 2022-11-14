<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $parent_category_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \App\Models\ProductCategory $parentCategory
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\ProductCategory> $children
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 */
class ProductCategory extends Model
{
    use HasFactory;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'parent_category_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'product_categories', 'parent_category_id', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_product_category');
    }
}
