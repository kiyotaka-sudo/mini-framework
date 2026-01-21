<?php

namespace App\Core;

abstract class Model
{
    protected static $table;

    public static function getTable()
    {
        if (static::$table) {
            return static::$table;
        }

        // Guess table name from class name (e.g. User -> users)
        $className = (new \ReflectionClass(static::class))->getShortName();
        return strtolower($className) . 's';
    }

    public static function all()
    {
        $db = Database::getInstance();
        $table = static::getTable();
        $stmt = $db->query("SELECT * FROM $table");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        $db = Database::getInstance();
        $table = static::getTable();
        $stmt = $db->query("SELECT * FROM $table WHERE id = :id", ['id' => $id]);
        return $stmt->fetch();
    }

    public static function create(array $data)
    {
        $db = Database::getInstance();
        $table = static::getTable();
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $db->query($sql, $data);
        
        return static::find($db->lastInsertId());
    }

    public static function update($id, array $data)
    {
        $db = Database::getInstance();
        $table = static::getTable();
        
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setString = implode(', ', $set);
        
        $data['id'] = $id;
        
        $sql = "UPDATE $table SET $setString WHERE id = :id";
        $db->query($sql, $data);
        
        return static::find($id);
    }

    public static function delete($id)
    {
        $db = Database::getInstance();
        $table = static::getTable();
        
        $sql = "DELETE FROM $table WHERE id = :id";
        $db->query($sql, ['id' => $id]);
        
        return true;
    }
}
