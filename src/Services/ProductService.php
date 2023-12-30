<?php

namespace App\Services;

use App\Traits\Cache;
use App\Models\Product;

class ProductService
{
    use Cache;

    public function __construct()
    {
        $this->cacheInit();
    }
    
    public function all(): mixed
    {
        if (!$this->cache->get('productList')) {
            $model = new Product;
            $products = $model->all();

            $productList = array_map(fn ($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
            ], $products);

            $this->cache->set('productList', $productList);
        }

        return $this->cache->get('productList');
    }
}
