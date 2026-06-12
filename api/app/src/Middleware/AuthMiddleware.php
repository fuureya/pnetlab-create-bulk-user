<?php

namespace App\Middleware;

class AuthMiddleware {
    public static function check() {
        // Ambil header dari fungsi getallheaders (Apache) atau dari $_SERVER (Nginx/FastCGI)
        $headers = function_exists('getallheaders') ? array_change_key_case(getallheaders(), CASE_LOWER) : [];
        $providedKey = $headers['x-api-key'] ?? $_SERVER['HTTP_X_API_KEY'] ?? null;
        
        $apiKey = trim(getenv('API_KEY'));

        // Cek apakah header x-api-key ada dan cocok
        if (!$providedKey || trim($providedKey) !== $apiKey) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized: Invalid API Key",
                "debug_hint" => "Pastikan header X-API-KEY sudah dikirim dengan benar"
            ]);
            exit;
        }
    }
}
