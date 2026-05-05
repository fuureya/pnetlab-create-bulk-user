<?php

namespace App\Controllers;

use App\Config\Database;
use PDO;

class UserController {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    public function index() {
        $query = "SELECT id, username, name, email, pod, role, expired_time FROM users ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(["status" => "success", "data" => $users]);
    }

    public function create($data) {
        if (empty($data['username']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Username and password are required"]);
            return;
        }

        // Cek duplicate username
        $check = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
        $check->execute([$data['username']]);
        if ($check->rowCount() > 0) {
            http_response_code(409);
            echo json_encode(["status" => "error", "message" => "Username already exists"]);
            return;
        }

        // Cari POD ID otomatis
        $pod_stmt = $this->conn->query("SELECT pod FROM users WHERE pod IS NOT NULL ORDER BY pod ASC");
        $used_pods = $pod_stmt->fetchAll(PDO::FETCH_COLUMN);
        $assigned_pod = 0;
        while (in_array($assigned_pod, $used_pods)) {
            $assigned_pod++;
        }

        $time_now = time();
        $hashed_password = hash('sha256', $data['password']);
        
        $query = "INSERT INTO users (username, email, name, password, role, offline, user_status, pod, online_time, expired_time)
                  VALUES (:username, :email, :name, :password, :role, 1, 1, :pod, :online_time, :expired_time)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'] ?? ($data['username'] . '@pnetlab.local'),
            ':name' => $data['name'] ?? 'PNETLab User',
            ':password' => $hashed_password,
            ':role' => $data['role'] ?? 1,
            ':pod' => $assigned_pod,
            ':online_time' => $time_now,
            ':expired_time' => isset($data['expired_days']) ? ($time_now + ($data['expired_days'] * 86400)) : null
        ]);

        echo json_encode([
            "status" => "success", 
            "message" => "User created successfully",
            "data" => ["username" => $data['username'], "pod" => $assigned_pod]
        ]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo json_encode(["status" => "success", "message" => "User deleted"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Delete failed"]);
        }
    }
}
