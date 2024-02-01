<?php

namespace core\Database;

use app\Models\LoginModel;
use app\Models\User;

class Database
{
    public \PDO $pdo;

    public function __construct($config = [])
    {
        $dsn = $config['DSN'] ?? '';
        $username = $config['USERNAME'] ?? 'root';
        $password = $config['PASSWORD'] ?? '';

        $this->pdo = new \PDO($dsn, $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function prepare(string $sql) : \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function insert(string $table, array $attributes, array $values): bool
    {
        $params = implode(',', array_map(fn($attr) => ":$attr", $attributes));
        $attributes = implode(',', $attributes);

        $statement = $this->prepare("INSERT INTO $table ($attributes) VALUES ($params)");

        foreach (explode(',', $attributes) as $attribute) {
            $statement->bindParam(":$attribute", $values[$attribute]);
        }

        return $statement->execute();
    }

    public function update(string $table, array $attributes, array $values, array $conditions): bool
    {
        $updateFields = implode(', ', array_map(fn($attr) => "$attr = :$attr", $attributes));
        $selectConditions = implode(' AND ', array_map(fn($attr) => "$attr = :$attr", array_keys($conditions)));

        $statement = $this->prepare("UPDATE $table SET $updateFields WHERE $selectConditions");

        // Bind values
        foreach ($values as $attribute => $value) {
            $statement->bindParam(":$attribute", $value);
        }

        // Bind conditions
        foreach ($conditions as $attribute => $condition) {
            $statement->bindParam(":$attribute", $condition);
        }

        return $statement->execute();
    }



    public function findOne(string $table, array $conditions = [], array $selectAttributes = ['*'], $class = null)
    {
        $selectAttributes = implode(',', $selectAttributes);
        $selectConditions = implode(' OR ', array_map(fn($attr) => "$attr = :$attr", array_keys($conditions)));


        $sql = "SELECT $selectAttributes FROM $table WHERE $selectConditions";
        $statement = $this->prepare($sql);

        foreach ($conditions as $attribute => $condition) {
            $statement->bindValue(":$attribute", $condition);
        }

        $statement->execute();

        return $statement->fetchObject($class);
    }
}