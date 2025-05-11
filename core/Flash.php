<?php


namespace Core;

use Core\Session;


class Flash
{
    public const SUCCESS = 'success';
    public const ERROR = 'errors';

    public static function set($key, $message): void
    {
        $messages = Session::get($key) ?? [];
        $messages[] = $message;
        Session::set($key, $messages);
    }

    public static function display($key): void
    {
        $messages = Session::get($key);
        if ($messages) {
            $class = ($key == self::SUCCESS) ? 'alert-success' : 'alert-danger';
            foreach ($messages as $message) {
                echo "<div class='flash-message $class'>$message</div>";
            }
            Session::delete($key);
        }
    }


    public static function exists($key)
    {
        return Session::get($key) ?? false;
    }
}
