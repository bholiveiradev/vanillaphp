<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Core\Http\{Request, Response};
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;

final class ProductController extends Controller
{
    public function index(Request $request, Response $response): void
    {
        $products = ProductService::productsCache();

        $response::json($products);
    }

    public function store(Request $request, Response $response): void
    {
        $data = $request::getInputs();

        Product::fill($data)->insert();

        $response::json([], Response::HTTP_CREATED);
    }

    public function show(Request $request, Response $response): void
    {
        $product = Product::find($request->id);

        $response::json($product->toArray());
    }

    public function update(Request $request, Response $response): void
    {
        $product = Product::find($request->id);

        $product->update($request::getInputs(), $product->id);

        $response::json([], Response::HTTP_OK);
    }

    public function delete(Request $request, Response $response): void
    {
        $product = Product::find($request->id);
        $product->delete();

        $response::json([], Response::HTTP_OK);
    }
}
