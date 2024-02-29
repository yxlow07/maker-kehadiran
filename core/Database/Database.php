<?php

namespace core\Database;

use app\Models\LoginModel;
use app\Models\User;
use core\Models\BaseModel;

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
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    public function prepare(string $sql) : \PDOStatement
    {
        return $this->pdo->prepare($sql);
    }

    public function insert(string $table, array $attributes, array|object $values): bool
    {
        $params = implode(',', array_map(fn($attr) => ":$attr", $attributes));
        $attributes = implode(',', $attributes);


        $statement = $this->prepare("INSERT INTO $table ($attributes) VALUES ($params)");


        foreach (explode(',', $attributes) as $attribute) {
            $value = !($values instanceof BaseModel) ? $values[$attribute] : $values->{$attribute};
            $statement->bindValue(":$attribute", $value);
        }

        return $statement->execute();
    }

    /**
     * This function differs from upsert function. Using this function, updated ids still will update the row using old row data
     * @param string $table
     * @param array $attributes
     * @param array|object $values If object is passed, must be instanceof BaseModel
     * @param array $conditions
     * @return bool
     */
    public function update(string $table, array $attributes, array|object $values, array $conditions, bool $needCheckIfExists = false): bool
    {
        $updateFields = implode(', ', array_map(fn($attr) => "$attr = :$attr", $attributes));
        $selectConditions = implode(' AND ', array_map(fn($attr) => "$attr = :$attr", array_keys($conditions)));

        $statement = $this->prepare("UPDATE $table SET $updateFields WHERE $selectConditions");

        // Bind values
        if (is_array($values)) {
            foreach ($values as $attribute => &$value) {
                $statement->bindValue(":$attribute", $value);
            }
        } else {
            foreach ($attributes as $attribute) {
                $statement->bindValue(":$attribute", $values->{$attribute});
            }
        }

        // Bind conditions
        foreach ($conditions as $attribute => &$condition) {
            $statement->bindValue(":$attribute", $condition);
        }

        if ($needCheckIfExists) {
            if ($this->findOne($table, $conditions)) {
                return $statement->execute();
            } else {
                return $this->insert($table, $attributes, $values);
            }
        } else {
            return $statement->execute();
        }
    }

    public function findOne(string $table, array $conditions = [], array $selectAttributes = ['*'], $class = null, bool $fetchObject = true)
    {
        $selectAttributes = implode(',', $selectAttributes);
        $selectConditions = implode(' OR ', array_map(fn($attr) => "$attr = :$attr", array_keys($conditions)));


        $sql = "SELECT $selectAttributes FROM $table WHERE $selectConditions";
        $statement = $this->prepare($sql);

        foreach ($conditions as $attribute => $condition) {
            $statement->bindValue(":$attribute", $condition);
        }

        $statement->execute();

        return $fetchObject ? $statement->fetchObject($class) : $statement->fetchColumn();
    }
}