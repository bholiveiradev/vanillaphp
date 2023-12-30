<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB\Model;
use App\Traits\Cache;

class Product extends Model
{
    use Cache;

    public function __construct(
        array $fields = [],
        protected string $table = 'products',
        protected array $attributes = ['name', 'price', 'stock']
    )
    {
        parent::__construct(table: $table, fields: $fields, attributes: $attributes);
        
        $this->cacheInit();
    }

    public function onInsert()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }

    public function onUpdate()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }

    public function onDelete()
    {
        if ($this->cache->get('productList')) {
            $this->cache->forget('productList');
        }
    }
}