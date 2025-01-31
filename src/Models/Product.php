<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DB\Model;
use App\Traits\Cache;

class Product extends Model
{
    use Cache;

    protected string $table = 'products';
    protected array $attributes = ['name', 'price', 'stock'];

    public function __construct(array $fields = [])
    {
        parent::__construct(fields: $fields);

        $this->cacheInit();
    }

    public function onInsert()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }

    public function onUpdate()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }

    public function onDelete()
    {
        if ($this->cache->get('product-list')) {
            $this->cache->forget('product-list');
        }
    }
}
