<?php

namespace App\Http\Controllers;

use App\Http\RequestData\ProductStoreData;
use App\Http\Transformers\ProductTransformer;
use App\Models\Product;
use App\Traits\HasMeta;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasMeta;

    public function index(Request $request, ProductTransformer $transformer)
    {
        $products = Product::query();

        $this->applyMeta($products);

        return $this->response->index($products, $transformer);
    }

    public function store(Request $request, ProductTransformer $transformer, ProductStoreData $data)
    {
        $product = new Product();
        $product->name = $data->name;
        $product->price = $data->price;
        $product->save();

        return $this->response->model($product, $transformer);
    }

    public function show(Product $product)
    {
        return $product;
    }
}
