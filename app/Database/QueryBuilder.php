<?php

declare(strict_types=1);

namespace App\Database;

class QueryBuilder
{
    public function __construct(
        private string $table,
        private string $select = '*',
        private string $where = '',
        private string $orderBy = '',
        private string $limit = '',
        private array  $bindings = [],
    )
    {
    }

    public function select(string $columns = '*'): self
    {
        $this->select = $columns;

        return $this;
    }

    public function where(string $field, string|int $value, string $operator = '='): self
    {
        if (empty($this->where)) {
            $this->where = "WHERE {$field} {$operator} ?";
        } else {
            $this->where = " {$this->where} AND {$field} {$operator} ?";
        }

        $this->bindings[] = $value;

        return $this;
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $this->orderBy = "ORDER BY {$column} {$direction}";

        return $this;
    }

    public function limit(int $count, int $offset = 0): self
    {
        $this->limit = "LIMIT {$count} OFFSET {$offset}";

        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT {$this->select} FROM {$this->table} {$this->where} {$this->orderBy} {$this->limit}";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll();
    }

    public function count(string $fields = '*'): int
    {
        $sql = "SELECT COUNT({$fields}) as count FROM {$this->table} {$this->where}";
        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) $stmt->fetch()['count'];
    }

    public function insert(array $data): string|false
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";

        $stmt = DB::getInstance()->prepare($sql);
        $stmt->execute(array_values($data));

        $lastInsertId = DB::getInstance()->lastInsertId();

        return $lastInsertId;
    }

    public function update(array $data): bool
    {
        $fields = array_map(function ($field) {
            return "{$field} = ?";
        }, array_keys($data));

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " {$this->where}";

        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute(array_merge(array_values($data), $this->bindings));

        return $result;
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table} {$this->where}";

        $stmt = DB::getInstance()->prepare($sql);
        $result = $stmt->execute($this->bindings);

        return $result;
    }
}
