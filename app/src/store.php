<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $name = trim($_POST['name']) ?: 'PNETLab User';
    $expired_days = (int)$_POST['expired'];

    if (empty($username) || empty($password)) {
        die("Username dan Password wajib diisi.");
    }

    // 1. Cek duplikasi username
    $check = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $check->execute([$username]);
    if ($check->fetch()) {
        die("Error: Username sudah terdaftar.");
    }

    // 2. Hash password SHA256 (Aturan PNETLab)
    $hashed_password = hash('sha256', $password);
    $email = $username . '@pnetlab.local';
    $time_now = time();

    // 3. Kalkulasi expired time
    $expired_time = null;
    if ($expired_days > 0) {
        $expired_time = $time_now + ($expired_days * 86400);
    }

    // 4. Cari POD ID kosong
    $pod_stmt = $pdo->query("SELECT pod FROM users WHERE pod IS NOT NULL ORDER BY pod ASC");
    $used_pods = $pod_stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $assigned_pod = 0;
    while (in_array($assigned_pod, $used_pods)) {
        $assigned_pod++;
    }

    // 5. Insert ke database
    try {
        $sql = "INSERT INTO users (username, email, name, password, role, offline, user_status, pod, online_time, expired_time) 
                VALUES (?, ?, ?, ?, 1, 1, 1, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$username, $email, $name, $hashed_password, $assigned_pod, $time_now, $expired_time]);

        header("Location: index.php?status=success");
        exit;
    } catch (Exception $e) {
        die("Gagal menyimpan data: " . $e->getMessage());
    }
}
?>
