<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Relations\Pivot;

class StockAttribute extends Pivot
{
    protected $table = 'stock_product_attribute_value';

    public $incrementing = false;
}
