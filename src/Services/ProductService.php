<?php

namespace App\Services;

use App\Traits\Cache;
use App\Models\Product;

class ProductService
{
    use Cache;
    
    public static function productsCache(): mixed
    {
        self::cacheInit();

        if (! self::$cache->get('productList')) {
            $products = Product::all('id', 'DESC');

            $productList = array_map(fn ($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
            ], $products);

            self::$cache->set('productList', $productList);
        }

        return self::$cache->get('productList');
    }
}
