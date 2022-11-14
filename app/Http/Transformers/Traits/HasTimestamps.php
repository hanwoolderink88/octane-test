<?php

namespace App\Http\Transformers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

trait HasTimestamps
{
    protected function getTimestamps(Model $model): array
    {
        $response = [];

        if (isset($model->updated_at) && $model->updated_at instanceof Carbon) {
            $response['updated_at'] = $model->updated_at->format('Y-m-d H:i');
        }

        if (isset($model->created_at) && $model->created_at instanceof Carbon) {
            $response['created_at'] = $model->created_at->format('Y-m-d H:i');
        }

        return $response;
    }
}
