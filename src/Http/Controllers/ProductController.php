<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\Http\Request;
use App\Services\ProductService;

final class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService
    ) {}

    public function index(): void
    {
        $products = $this->productService->getFromCache();

        view('products/index', ['products' => $products]);
    }

    public function create(): void
    {
        view('products/create');
    }

    public function store(Request $request): void
    {
        $data = $request->getInputs();

        $this->productService->save($data);

        redirect('/products');
    }

    public function show(Request $request): void
    {
        $product = $this->productService->findOne($request->id);

        view('products/show', ['product' => $product]);
    }

    public function edit(Request $request): void
    {
        $product = $this->productService->findOne($request->id);

        view('products/edit', ['product' => $product]);
    }

    public function update(Request $request): void
    {
        $this->productService->update($request->id, $request::getInputs());

        redirect('/products');
    }

    public function delete(Request $request): void
    {
        $this->productService->remove($request->id);

        redirect('/products');
    }
}
