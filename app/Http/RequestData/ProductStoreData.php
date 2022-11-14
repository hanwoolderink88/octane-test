<?php

namespace App\Http\RequestData;

use App\Validation\Rule;

class ProductStoreData extends AbstractData
{
    #[Rule('required|string')]
    public string $name;

    #[Rule('required|numeric|regex:/^\d+(\.\d{1,2})?$/')]
    public float $price;
}
