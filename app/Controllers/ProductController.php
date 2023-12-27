<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\Controller;
use App\Http\Request;
use App\Models\Product;
use App\Support\Session;
use App\UseCases\ProductUseCase;

class ProductController extends Controller
{
    public function index(): void
    {
        $products = (new ProductUseCase())->all();

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