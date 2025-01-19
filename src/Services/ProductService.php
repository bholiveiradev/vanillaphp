<?php

declare(strict_types=1);

namespace App\Services;

use App\Traits\Cache;
use App\Models\Product;

class ProductService
{
    use Cache;
    
    public static function productsCache(): mixed
    {
        self::cacheInit();

        if (! self::$cache->get('product-list')) {
            $products = Product::all('id', 'DESC');

            $productList = array_map(fn ($item) => [
                'id'    => $item->id,
                'name'  => $item->name,
                'price' => $item->price,
                'stock' => $item->stock,
            ], $products);

            self::$cache->set('product-list', $productList);
        }

        return self::$cache->get('product-list');
    }
}
