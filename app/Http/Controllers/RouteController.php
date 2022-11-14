<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route as ConcreteRoute;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class RouteController extends Controller
{
    public function index(): Collection
    {
        return collect(Route::getRoutes()->getRoutes())
            ->filter(function (ConcreteRoute $route) {
                return !str_contains($route->uri(), 'telescope') && !str_contains($route->uri(), '_ignition');
            })
            ->sortBy(fn(ConcreteRoute $route) => $route->uri())
            ->map(function (ConcreteRoute $route) {

                return implode(' | ', [
                    Str::padRight(implode('|', $route->methods()), 10),
                    Str::padRight($route->getName(), 20),
                    Str::start($route->uri(), '/'),
                ]);
            })
            ->values();
    }
}
