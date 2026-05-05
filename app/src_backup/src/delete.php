<?php
require 'config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: index.php?status=deleted");
        exit;
    } catch (Exception $e) {
        die("Gagal menghapus data: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
    exit;
}
?>
