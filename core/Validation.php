<?php

namespace Core;

class Validation
{

    public static function string($value, $min = 0, $max = PHP_INT_MAX)
    {
        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function intVal($value, $min = 0, $max = PHP_INT_MAX)
    {
        return (int)$value >= $min && (int)$value <= $max;
    }

    public static function email($value)
    {
        if ($value == '') return true;
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function sanitize($value)
    {
        return htmlspecialchars(trim($value));
    }

    private static function getConnection()
    {
        $config = require basePath('config/_db.php');
        return Database::getInstance($config)->getConnection();
    }

    public static function exists($table, $column, $value, $message)
    {
        $db = self::getConnection();
        $query = "SELECT COUNT(*) as cnt from $table where $column = :val";
        $result = $db->query($query, ['val' => $value])->fetch();
        if ($result->cnt === 0) return false;
        return $message;
    }

    public static function checkOldPassword($table, $id, $inputPassword, $colName = 'password')
    {
        $db = self::getConnection();
        $query = "SELECT $colName FROM $table WHERE id = :id";
        $dbPassword = $db->query($query, ['id' => $id])->fetch()->{$colName};
        return password_verify($inputPassword, $dbPassword);
    }
}
