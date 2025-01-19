<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Request;
use App\Models\Product;
use App\Support\Session;
use App\Services\ProductService;

final class ProductController extends Controller
{
    public function index(): void
    {
        $products = ProductService::productsCache();

        view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        view('products/create');
    }

    public function store(Request $request): void
    {
        $data = $request::getInputs();

        Product::fill($data)->insert();

        Session::flash('success', 'O produto foi criado!');

        redirect('/products');
    }

    public function show(Request $request): void
    {
        $product = Product::find($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request): void
    {
        $product = Product::find($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request): void
    {
        $product = Product::find($request->id);

        $product->update($request::getInputs(), $product->id);

        Session::flash('success', 'O produto foi salvo!');

        redirect('/products');
    }

    public function delete(Request $request): void
    {
        $product = Product::find($request->id);
        $product->delete();

        redirect('/products');
    }
}