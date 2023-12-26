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
    public function index()
    {
        $products = (new ProductUseCase())->all();

        view('products/index', ['products' => $products]);
    }

    public function create()
    {
        view('products/create');
    }

    public function store(Request $request)
    {
        $product = new Product();
        $product->fill($request->getAll());
        $product->insert();

        Session::flash('success', 'O produto foi criado!');

        redirect('/products');
    }

    public function show(Request $request)
    {
        $product = (new Product())->find($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request)
    {
        $product = (new Product())->find($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request)
    {
        $product = (new Product())->find($request->id);
        $product->fill($request->getAll());
        $product->update();

        redirect('/products');
    }

    public function delete(Request $request)
    {
        $product = (new Product())->find($request->id);
        $product->delete();

        redirect('/products');
    }
}