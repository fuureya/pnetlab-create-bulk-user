<?php

namespace App\Middleware;

class AuthMiddleware {
    public static function check() {
        $headers = getallheaders();
        $apiKey = getenv('API_KEY');

        if (!isset($headers['X-API-KEY']) || $headers['X-API-KEY'] !== $apiKey) {
            http_response_code(401);
            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized: Invalid API Key"
            ]);
            exit;
        }
    }
}
