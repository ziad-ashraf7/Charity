<?php

use Core\Flash;

function basePath($path): string
{
    return __DIR__ . "/" . $path;
}

function loadView($name, $data = []): void
{
    $viewPath = basePath("app/views/{$name}.view.php");
    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View '{$name} not found!'";
    }
}

function loadPartial($name, $data = []): void
{
    $viewPath = basePath("app/views/partials/{$name}.php");

    if (file_exists($viewPath)) {
        extract($data);
        require $viewPath;
    } else {
        echo "View '{$name} not found!'";
    }
}

function inspect($variable): void
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}

function inspectAndDie($variable): void
{
    inspect($variable);
    die();
}

function redirect($route): void
{
    header("Location: {$route}");
}

function sanitize($value): string
{
    return htmlspecialchars(trim($value));
}

function formatNumber($number): string
{
    return number_format($number, 2, '.', ',');
}

function fetchFields(&$requiredFields): array
{
    $data = [];
    foreach ($_POST as $key => $value) {
        if (in_array($key, $requiredFields)) {
            $data[$key] = sanitize($value);
        }
    }
    return $data;
}

function viewErrorsIfExist(&$errors)
{
    if (!empty($errors)) {
        foreach ($errors as $error => $message) {
            Flash::set(Flash::ERROR, $message);
        }
        redirect("/register");
    }
}