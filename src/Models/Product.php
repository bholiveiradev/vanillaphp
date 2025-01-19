<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB\Model;
use App\Traits\Cache;

class Product extends Model
{
    use Cache;

    protected static string $table = 'products';
    protected static array $attributes = ['name', 'price', 'stock'];

    public function __construct(array $fields = [])
    {
        parent::__construct(fields: $fields);

        self::cacheInit();
    }

    public static function onInsert()
    {
        if (self::$cache->get('product-list')) {
            self::$cache->forget('product-list');
        }
    }

    public static function onUpdate()
    {
        if (self::$cache->get('product-list')) {
            self::$cache->forget('product-list');
        }
    }

    public static function onDelete()
    {
        if (self::$cache->get('product-list')) {
            self::$cache->forget('product-list');
        }
    }
}
