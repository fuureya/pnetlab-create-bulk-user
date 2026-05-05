<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With, X-API-KEY");

// Simple Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

// Load .env manually for native PHP
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        putenv(trim($name) . "=" . trim($value));
    }
}

use App\Middleware\AuthMiddleware;
use App\Controllers\UserController;

// Security Check
AuthMiddleware::check();

$userController = new UserController();
$requestMethod = $_SERVER["REQUEST_METHOD"];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Simple Routing
// Expected URI: /users or /users/:id
if ($uri[1] !== 'users') {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
    exit;
}

switch ($requestMethod) {
    case 'GET':
        $userController->index();
        break;
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        $userController->create($data);
        break;
    case 'DELETE':
        $id = $uri[2] ?? null;
        if ($id) {
            $userController->delete($id);
        } else {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "ID required"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["status" => "error", "message" => "Method not allowed"]);
        break;
}
