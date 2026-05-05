<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)$_POST['id'];
    $password = $_POST['password'];
    $name = trim($_POST['name']);
    $expired_choice = $_POST['expired'];

    try {
        // Build query dynamically
        $updates = ["name = ?"];
        $params = [$name];

        if (!empty($password)) {
            $updates[] = "password = ?";
            $params[] = hash('sha256', $password);
        }

        if ($expired_choice !== 'keep') {
            $updates[] = "expired_time = ?";
            if ($expired_choice == '0') {
                $params[] = null;
            } else {
                $params[] = time() + ((int)$expired_choice * 86400);
            }
        }

        $params[] = $id;
        $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header("Location: index.php?status=updated");
        exit;
    } catch (Exception $e) {
        die("Gagal memperbarui data: " . $e->getMessage());
    }
}
?>
