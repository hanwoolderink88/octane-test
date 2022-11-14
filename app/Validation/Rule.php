<?php

namespace App\Validation;

#[\Attribute]
class Rule
{
    public function __construct(public string $rule)
    {
    }
}
