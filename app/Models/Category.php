<?php

namespace App\Models;

use App\Models\Pivot\ProductCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $parent_category_id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Category $parentCategory
 * @property Collection<int, Category> $children
 * @property Collection<int, Product> $products
 */
class Category extends Model
{
    use HasFactory;

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_category_id');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'product_category')
            ->using(ProductCategory::class);
    }
}
