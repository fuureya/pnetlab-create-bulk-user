<?php

namespace App\Middleware;

class AuthMiddleware {
    public static function check() {
        // Ambil semua header secara case-insensitive
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        $apiKey = trim(getenv('API_KEY'));

        // Cek apakah header x-api-key ada
        if (!isset($headers['x-api-key']) || trim($headers['x-api-key']) !== $apiKey) {
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
