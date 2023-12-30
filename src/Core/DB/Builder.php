<?php

declare(strict_types=1);

namespace App\Core\DB;

class Builder
{
    private static string $table;
    private static string $select;
    private static string $where;
    private static string $orderBy;
    private static string $limit;
    private static array  $bindings;

    public function __construct(
        string $table,
        string $select = '*',
        string $where = '',
        string $orderBy = '',
        string $limit = '',
        array  $bindings = [],
    )
    {
        self::$table = $table;
        self::$select = $select;
        self::$where = $where;
        self::$orderBy = $orderBy;
        self::$limit = $limit;
        self::$bindings = $bindings;
    }

    public static function select(string $columns = '*'): self
    {
        self::$select = $columns;

        return new static(
            self::$table,
            self::$select,
            self::$where,
            self::$orderBy,
            self::$limit,
            self::$bindings,
        );
    }

    public static function where(string $field, string|int $value, string $operator = '='): self
    {
        if (empty(self::$where)) {
            self::$where = "WHERE {$field} {$operator} ?";
        } else {
            self::$where = " ". self::$where . "AND {$field} {$operator} ?";
        }

        self::$bindings[] = $value;

        return new static(
            self::$table,
            self::$select,
            self::$where,
            self::$orderBy,
            self::$limit,
            self::$bindings,
        );
    }

    public static function orderBy(string $column, string $direction = 'ASC'): self
    {
        self::$orderBy = "ORDER BY {$column} {$direction}";

        return new static(
            self::$table,
            self::$select,
            self::$where,
            self::$orderBy,
            self::$limit,
            self::$bindings,
        );
    }

    public static function limit(int $count, int $offset = 0): self
    {
        self::$limit = "LIMIT {$count} OFFSET {$offset}";

        return new static(
            self::$table,
            self::$select,
            self::$where,
            self::$orderBy,
            self::$limit,
            self::$bindings,
        );
    }

    public static function get(): array
    {
        $sql = "SELECT " . self::$select . " FROM " . self::$table;

        if (!empty(self::$where)) {
            $sql .= " " . self::$where;
        }
        
        if (!empty(self::$orderBy)) {
            $sql .= " " . self::$orderBy;
        }
        
        if (!empty(self::$limit)) {
            $sql .= " " . self::$limit;
        }
        
        $stmt = DB::pdo()->prepare($sql);
        $stmt->execute(self::$bindings);

        return $stmt->fetchAll();
    }

    public static function count(string $fields = '*'): int
    {
        $sql = "SELECT COUNT({$fields}) as count FROM " . self::$table . " " . self::$where;
        $stmt = DB::pdo()->prepare($sql);
        $stmt->execute(self::$bindings);

        return (int) $stmt->fetch()['count'];
    }

    public static function insert(array $data): string|false
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO " . self::$table . " ({$fields}) VALUES ({$values})";

        $stmt = DB::pdo()->prepare($sql);

        $stmt->execute(array_values($data));

        $lastInsertId = DB::pdo()->lastInsertId();

        return $lastInsertId;
    }

    public static function update(array $data): bool
    {
        $fields = array_map(function ($field) {
            return "{$field} = ?";
        }, array_keys($data));

        $sql = "UPDATE " . self::$table . " SET " . implode(', ', $fields) . " " . self::$where;

        $stmt = DB::pdo()->prepare($sql);
        $result = $stmt->execute(array_merge(array_values($data), self::$bindings));

        return $result;
    }

    public static function delete(): bool
    {
        $sql = "DELETE FROM " . self::$table . " " . self::$where;

        $stmt = DB::pdo()->prepare($sql);
        $result = $stmt->execute(self::$bindings);

        return $result;
    }
}
