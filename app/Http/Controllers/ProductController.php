<?php

namespace App\Http\Controllers;

use App\Http\RequestData\ProductStoreData;
use App\Http\Transformers\ProductModelTransformer;
use App\Models\Product;
use App\Traits\HasMeta;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasMeta;

    public function index(Request $request, ProductModelTransformer $transformer): JsonResponse
    {
        $products = Product::query();

        $this->applyMeta($products);

        return $this->response->index($products, $transformer);
    }

    public function store(Request $request, ProductModelTransformer $transformer, ProductStoreData $data): JsonResponse
    {
        $product = new Product();
        $product->name = $data->name;
        $product->price = $data->price;
        $product->save();

        return $this->response->model($product, $transformer);
    }

    public function show(Product $product, ProductModelTransformer $transformer): JsonResponse
    {
        $this->applyIncludeToModel($product);

        return $this->response->model($product, $transformer);
    }
}
