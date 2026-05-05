<?php

use App\Controllers\UserController;

$userController = new UserController();
$requestMethod = $_SERVER["REQUEST_METHOD"];

// Ambil path setelah domain/bulkuser/api/v2/
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/bulkuser/api/v2/', '', $path);
$path = trim($path, '/');
$uri = explode('/', $path);

// Router
// Endpoint: users
if (isset($uri[0]) && $uri[0] === 'users') {
    switch ($requestMethod) {
        case 'GET':
            $userController->index();
            break;
        case 'POST':
            $data = json_decode(file_get_contents("php://input"), true);
            $userController->create($data);
            break;
        case 'DELETE':
            $id = $uri[1] ?? null;
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
} else {
    http_response_code(404);
    echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
}
