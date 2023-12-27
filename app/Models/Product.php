<?php

declare(strict_types=1);

namespace App\Models;

use App\Database\ActiveRecord;
use App\Traits\Cache;

class Product extends ActiveRecord
{
    use Cache;

    public function __construct(
        array $fields = [],
        protected string $table = 'products',
        protected array $attributes = ['name', 'price', 'stock']
    )
    {
        $this->cacheInit();
        
        parent::__construct(
            table: $table,
            fields: $fields,
            attributes: $attributes
        );
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