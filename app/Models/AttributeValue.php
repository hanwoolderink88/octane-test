<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class AttributeValue extends Model
{
    use HasFactory;

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
