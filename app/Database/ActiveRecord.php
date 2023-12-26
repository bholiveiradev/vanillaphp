<?php

declare(strict_types=1);

namespace App\Database;

class ActiveRecord
{
    protected string $table;
    protected string $primaryKey = 'id';
    protected array  $attributes = [];
    private array    $fields = [];

    public function __construct(array $fields = [])
    {
        $this->fields = $fields;
    }

    public function __get(string $name): mixed
    {
        return isset($this->fields[$name]) ? $this->fields[$name] : null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->fields[$name] = $value;
    }

    public function fill(array $data): void
    {
        foreach ($this->attributes as $key => $attribute) {
            if (isset($data[$attribute])) {
                $this->$attribute = $data[$attribute];
            }
            unset($this->fields[$key]);
        }
    }

    public function find(string|int $id): self
    {
        $result = (new QueryBuilder($this->table))
            ->where($this->primaryKey, $id)
            ->get()[0];

        return new static($result);
    }

    public function all(): array
    {
        $result = (new QueryBuilder($this->table))
            ->select()
            ->get();

        $items = array_map(function ($item) {
            return new static($item);
        }, $result);

        return $items;
    }

    public function onInsert()
    {
    }

    public function insert(array $data = []): string|false
    {
        $fields = !empty($data) ? $data : $this->fields;
        
        unset($fields[$this->primaryKey]);

        $result = (new QueryBuilder($this->table))->insert($fields);

        $this->fields[$this->primaryKey] = $result;

        $this->onInsert();

        return $result;
    }

    public function onUpdate()
    {  
    }

    public function update(array $data = [], ?int $id = null): bool
    {
        $fields = !empty($data) ? $data : $this->fields;
        $id = !is_null($id) ? $id : $fields[$this->primaryKey];

        unset($fields[$this->primaryKey]);
        
        $result = (new QueryBuilder($this->table))
            ->where($this->primaryKey, $id)
            ->update($fields);
        
        $this->onUpdate();

        return $result;
    }

    public function onDelete()
    {
    }

    public function delete(?int $id = null): bool
    {
        $id = !is_null($id) ? $id : $this->fields[$this->primaryKey];

        $result = (new QueryBuilder($this->table))
            ->where($this->primaryKey, $id)
            ->delete();

        $this->onDelete();
        
        return $result;
    }
}
