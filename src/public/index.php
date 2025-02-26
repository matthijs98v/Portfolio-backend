<?php

// Set the app

// Set the headers for api request
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");
header('Content-Type: application/json');
// For making a public dir, so you can access the public folder
const PUBLIC_DIR = __dir__;
use Lib\Router\Router;

try {
    // The lib
    require_once __DIR__ . '/../vendor/autoload.php';
    // Config
    require_once __DIR__ . '/../config/config.php';
    // Instance of the url parser
    $router = new Router();
    // Add the router and controller
    require_once __DIR__ . '/../routes/api.php';
    // To start the parser
    $router_return = $router->run();
    $result = json_encode([
        'success' => true,
        ...$router_return['return']
    ]);
    echo $result;
    exit;
} catch (Exception $e) {
    header('Content-Type: application/json');
    // Make error into json if needed
    $error = json_decode($e->getMessage()) !== null ?
        json_decode($e->getMessage())
        : $e->getMessage();
    echo json_encode([
        'success' => false,
        'error' => $error
    ]);
    exit;
}
