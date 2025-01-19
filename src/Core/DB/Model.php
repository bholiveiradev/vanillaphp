<?php

declare(strict_types=1);

namespace App\Core\DB;

abstract class Model implements \JsonSerializable
{
    protected static string $table;
    protected static array $attributes = [];
    protected static string $primaryKey = 'id';
    public static array $filled = [];
    private array $fields = [];
    
    private static ?array $primary = null;

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

    public static function fill(array $data): self
    {
        $fields = [];

        foreach ($data as $key => $value) {
            if (in_array($key, static::$attributes)) {
                $fields[$key] = $value;
            }
        }

        static::$filled = $fields;

        return new static($fields);
    }

    public function toArray(): array
    {
        return $this->fields;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public static function find(string|int $id): self
    {
        $result = Builder::table(static::$table)->where(static::$primaryKey, $id)->get()[0];

        static::$primary[static::$primaryKey] = $result[static::$primaryKey];

        return new static($result);
    }

    public static function all(string $orderColumn = 'id', string $order = 'ASC'): array
    {
        $result = Builder::table(static::$table)->orderBy($orderColumn, $order)->get();

        $items = array_map(function ($fields) {
            return new static($fields);
        }, $result);

        return $items;
    }

    public static function onInsert()
    {
        // Lógica para evento de inserção, se necessário
    }

    public static function insert(array $data = []): string|false
    {
        $fields = !empty($data) ? $data : static::$filled;

        unset($fields[static::$primaryKey]);

        $result =Builder::table(static::$table)->insert($fields);

        static::onInsert();

        return $result;
    }

    public static function onUpdate()
    {
        // Lógica para evento de atualização, se necessário
    }

    public static function update(array $data = [], ?int $id = null): bool
    {
        static::fill($data);

        $fields = !empty($data) ? static::$filled : [];
        $id = !is_null($id) ? $id : static::$primary[static::$primaryKey];

        $result = Builder::table(static::$table)->where(static::$primaryKey, $id)->update($fields);

        static::onUpdate();

        return $result;
    }

    public static function onDelete()
    {
        // Lógica para evento de exclusão, se necessário
    }

    public static function delete(?int $id = null): bool
    {
        $id = !is_null($id) ? $id : static::$primary[static::$primaryKey];

        $result = Builder::table(static::$table)->where(static::$primaryKey, $id)->delete();

        static::onDelete();

        return $result;
    }
}
