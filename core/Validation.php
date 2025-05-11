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
        $query = 'SELECT COUNT(*) from users where email = :email';
        $result = $db->query($query, ['email' => $value])->fetch();
        var_dump($result);
    }
}
