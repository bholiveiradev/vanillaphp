<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Request;
use App\Models\Product;
use App\Support\Session;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function index(): void
    {
        $service = new ProductService;
        $products = $service->all();

        view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        view('products/create');
    }

    public function store(Request $request): void
    {
        $product = new Product();
        $product->fill($request->getAll());
        $product->insert();

        Session::flash('success', 'O produto foi criado!');

        redirect('/products');
    }

    public function show(Request $request): void
    {
        $product = (new Product())->find($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request): void
    {
        $product = (new Product())->find($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request): void
    {
        $product = (new Product())->find($request->id);
        $product->fill($request->getAll());
        $product->update();

        Session::flash('success', 'O produto foi salvo!');

        redirect('/products');
    }

    public function delete(Request $request): void
    {
        $product = (new Product())->find($request->id);
        $product->delete();

        redirect('/products');
    }
}