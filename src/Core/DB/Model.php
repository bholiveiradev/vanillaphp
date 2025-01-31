<?php

declare(strict_types=1);

namespace App\Core\DB;

abstract class Model implements \JsonSerializable
{
    protected string $table;
    protected array $attributes = [];
    protected string $primaryKey = 'id';
    public array $filled = [];
    private array $fields = [];

    private ?array $primary = null;

    public function __construct(array $fields)
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

    public function fill(array $data): self
    {
        $fields = [];

        foreach ($data as $key => $value) {
            if (in_array($key, $this->attributes)) {
                $fields[$key] = $value;
            }
        }

        $this->filled = $fields;

        return $this;
    }

    public function toArray(): array
    {
        return $this->fields;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function find(string|int $id): self
    {
        $result = Builder::table($this->table)->where($this->primaryKey, $id)->get()[0];

        $this->primary[$this->primaryKey] = $result[$this->primaryKey];

        return new $this($result);
    }

    public function all(string $orderColumn = 'id', string $order = 'ASC'): array
    {
        $result = Builder::table($this->table)->orderBy($orderColumn, $order)->get();

        $items = array_map(function ($fields) {
            return new static($fields);
        }, $result);

        return $items;
    }

    public function onInsert()
    {
        // Lógica para evento de inserção, se necessário
    }

    public function insert(array $data = []): string|false
    {
        $fields = !empty($data) ? $data : $this->filled;

        unset($fields[$this->primaryKey]);

        $result = Builder::table($this->table)->insert($fields);

        $this->onInsert();

        return $result;
    }

    public function onUpdate()
    {
        // Lógica para evento de atualização, se necessário
    }

    public function update(array $data = [], string|int|null $id = null): bool
    {
        $this->fill($data);

        $fields = !empty($data) ? $this->filled : [];
        $id = !is_null($id) ? $id : $this->primary[$this->primaryKey];

        $result = Builder::table($this->table)->where($this->primaryKey, $id)->update($fields);

        $this->onUpdate();

        return $result;
    }

    public function onDelete()
    {
        // Lógica para evento de exclusão, se necessário
    }

    public function delete(string|int|null $id = null): bool
    {
        $id = null !== $id ? $id : $this->primary[$this->primaryKey];

        $result = Builder::table($this->table)->where($this->primaryKey, $id)->delete();

        $this->onDelete();

        return $result;
    }
}
