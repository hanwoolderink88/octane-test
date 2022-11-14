<?php

namespace App\Http\RequestData;

use App\Validation\Rule;
use Illuminate\Http\Request;

abstract class AbstractData
{
    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function boot(Request $request): void
    {
        $validated = $request->validate($this->rules());

        foreach ($validated as $key => $value) {
            $this->$key = $value;
        }
    }

    public function rules(): array
    {
        // todo: this should be hard cached.. in files or smth
        $reflectionClass = new \ReflectionClass($this);

        $rules = [];

        foreach ($reflectionClass->getProperties() as $property) {

            $attributes = $property->getAttributes(Rule::class);

            foreach ($attributes as $attribute) {
                $listener = $attribute->newInstance();

                $rules[$property->getName()] = $listener->rule;
            }
        }

        return $rules;
    }
}
