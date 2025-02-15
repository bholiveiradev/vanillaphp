<?php

declare(strict_types=1);

namespace App\Core\DB;

class Builder
{
    public function __construct(
        private string $table,
        private string $select = '*',
        private string $where = '',
        private string $orderBy = '',
        private string $limit = '',
        private array  $bindings = []
    )
    {}

    public static function table(string $table): self
    {
        return new static($table);
    }

    public function select(string $columns = '*'): self
    {
        return $this->newInstance($this->table, $columns, $this->where, $this->orderBy, $this->limit, $this->bindings);
    }

    public function where(string $field, string|int $value, string $operator = '='): self
    {
        $where = empty($this->where) ? "WHERE {$field} {$operator} ?" : "{$this->where} AND {$field} {$operator} ?";
        $bindings = array_merge($this->bindings, [$value]);

        return $this->newInstance($this->table, $this->select, $where, $this->orderBy, $this->limit, $bindings);
    }

    public function orderBy(string $column, string $direction = 'ASC'): self
    {
        $orderBy = "ORDER BY {$column} {$direction}";

        return $this->newInstance($this->table, $this->select, $this->where, $orderBy, $this->limit, $this->bindings);
    }

    public function limit(int $count, int $offset = 0): self
    {
        $limit = "LIMIT {$count} OFFSET {$offset}";

        return $this->newInstance($this->table, $this->select, $this->where, $this->orderBy, $limit, $this->bindings);
    }

    public function get(): array
    {
        $sql = "SELECT {$this->select} FROM {$this->table}";

        if (! empty($this->where)) {
            $sql .= " {$this->where}";
        }

        if (! empty($this->orderBy)) {
            $sql .= " {$this->orderBy}";
        }

        if (! empty($this->limit)) {
            $sql .= " {$this->limit}";
        }

        $stmt = DB::pdo()->prepare($sql);
        $stmt->execute($this->bindings);

        return $stmt->fetchAll();
    }

    public function count(string $fields = '*'): int
    {
        $sql = "SELECT COUNT({$fields}) as count FROM {$this->table} {$this->where}";
        $stmt = DB::pdo()->prepare($sql);
        $stmt->execute($this->bindings);

        return (int) $stmt->fetch()['count'];
    }

    public function insert(array $data): string|false
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$values})";
        $stmt = DB::pdo()->prepare($sql);
        $stmt->execute(array_values($data));

        return DB::pdo()->lastInsertId();
    }

    public function update(array $data): bool
    {
        $fields = array_map(function ($field) {
            return "{$field} = ?";
        }, array_keys($data));

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " {$this->where}";
        $stmt = DB::pdo()->prepare($sql);
        
        return $stmt->execute(array_merge(array_values($data), $this->bindings));
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table} {$this->where}";
        $stmt = DB::pdo()->prepare($sql);
        
        return $stmt->execute($this->bindings);
    }

    private function newInstance(string $table, string $select, string $where, string $orderBy, string $limit, array $bindings): self
    {
        return new static($table, $select, $where, $orderBy, $limit, $bindings);
    }
}
