<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\DB\Model;
use App\Traits\Cache;
use App\Models\Product;
use App\Support\Session;

class ProductService
{
    use Cache;

    public function __construct(private Product $model)
    {}

    public function getFromCache(): mixed
    {
        $this->cacheInit();

        if (! $this->cache->get('product-list')) {
            $products = $this->model->all('id', 'DESC');

            $productList = array_map(fn ($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
                'stock' => $item->stock,
            ], $products);

            $this->cache->set('product-list', $productList);
        }

        return $this->cache->get('product-list');
    }

    public function save(array $data): void
    {
        $this->model->fill($data)->insert();

        Session::flash('success', 'O produto foi criado!');
    }

    public function findOne(string $id): Model
    {
        return $this->model->find($id);
    }

    public function update(string|int $id, array $data): void
    {
        $this->model->update($data, $id);

        Session::flash('success', 'O produto foi salvo!');
    }

    public function remove(string|int $id): void
    {
        $this->model->delete($id);
    }
}
