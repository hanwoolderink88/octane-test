<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Attribute extends Model
{
    use HasFactory;

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
