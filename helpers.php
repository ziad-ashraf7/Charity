<?php

use Core\Flash;
use Core\Validation;

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
        echo "View '{$name}' not found!'";
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

function inspect(...$variable): void
{
    echo "<pre>";
    var_dump(...$variable);
    echo "</pre>";
}

function inspectAndDie(...$variable): void
{
    inspect(...$variable);
    die();
}

function redirect($route): void
{
    header("Location: {$route}");
    exit();
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

function viewErrorsIfExist(&$errors, $view): void
{
    if (!empty($errors)) {
        foreach ($errors as $error => $message) {
            Flash::set(Flash::ERROR, $message);
        }
        redirect($view);
    }
}

function checkRequiredFields(&$requiredFields, &$data): array
{
    $errors = [];
    foreach ($requiredFields as $field) {
        if(is_array($field)){
            if ((!isset($data[$field[0]]) || $data[$field[0]] == '') && $field[1] === true) { //  || !strlen($data[$field]) || !Validation::string($data[$field]) {
                $errors[$field[0]] = ucfirst($field[0]) . ' is required';
            }
        }
        else if (!isset($data[$field]) || $data[$field] == '') { //  || !strlen($data[$field]) || !Validation::string($data[$field]) {
            $errors[$field] = ucfirst($field) . ' is required';
        }
    }
    return $errors;
}

function getImage($path, $imgName): string
{
    if ($imgName === null) return '/assets/uploads/noImg.jpg';
    return "/assets/uploads/$path/$imgName";
}

function validateGETQueryParam($name, $path)
{
    if (!isset($_GET[$name])) {
        Flash::set(Flash::ERROR, "$name is required in URL");
        redirect($path);
    }
}