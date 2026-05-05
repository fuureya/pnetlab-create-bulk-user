#!/usr/bin/php
<?php

// Pastikan dijalankan dari CLI
if (php_sapi_name() !== 'cli') {
    die("Script ini hanya bisa dijalankan melalui terminal/CLI.\n");
}

echo "=========================================\n";
echo "    PNETLAB USER GENERATOR (CLI)\n";
echo "=========================================\n\n";

// ==========================================
// 1. INPUT INTERAKTIF
// ==========================================
$new_username = trim(readline("Masukkan Username : "));
if (empty($new_username)) {
    die("Error: Username tidak boleh kosong.\n");
}

$new_password = trim(readline("Masukkan Password : "));
if (empty($new_password)) {
    die("Error: Password tidak boleh kosong.\n");
}

echo "\nPilih batas kedaluwarsa akun (Expired):\n";
echo "[0] Tidak ada expired (Selamanya)\n";
echo "[1] 1 Hari\n";
echo "[2] 2 Hari\n";
echo "[3] 3 Hari\n";
echo "[7] 1 Minggu (7 Hari)\n";
echo "[30] 1 Bulan (30 Hari)\n";
$expire_choice = trim(readline("Pilih [0/1/2/3/7/30]: "));

if (!is_numeric($expire_choice)) {
    die("Error: Pilihan expired tidak valid.\n");
}

$expire_days = (int)$expire_choice;

// Hitung unix timestamp expired_time
// PNETLab mengecek expiration berdasarkan waktu timestamp saat ini vs database
$time_now = time();
if ($expire_days > 0) {
    // Tambahkan jumlah hari * 24 jam * 60 menit * 60 detik
    $expired_time = $time_now + ($expire_days * 86400);
    echo "Akun akan kedaluwarsa pada: " . date('Y-m-d H:i:s', $expired_time) . "\n\n";
} else {
    $expired_time = null; // Tidak ada kedaluwarsa
    echo "Akun dibuat tanpa batas waktu (Selamanya).\n\n";
}

// Konfigurasi Default untuk Akun
$new_role     = 1; // 1 = User Biasa (Role Admin adalah 0 di PNETLab)
$new_name     = 'PNETLab User';
$new_email    = $new_username . '@pnetlab.local';

// Hash menggunakan sha256 sesuai aturan PNETLab
$hashed_password = hash('sha256', $new_password);

// ==========================================
// 2. KONEKSI & INSERT DATABASE
// ==========================================
$db_host = 'localhost';
$db_name = 'pnetlab_db';
$db_user = 'yourusername';
$db_pass = 'yourpass';

echo "Memproses pembuatan akun...\n";

try {
    $db = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Cek apakah username sudah ada
    $check_stmt = $db->prepare("SELECT username FROM users WHERE username = :username");
    $check_stmt->bindParam(':username', $new_username, PDO::PARAM_STR);
    $check_stmt->execute();

    if ($check_stmt->rowCount() > 0) {
        die("GAGAL: Username '{$new_username}' sudah terdaftar di database.\n");
    }

    // Cari POD ID yang kosong
    $pod_stmt = $db->query("SELECT pod FROM users WHERE pod IS NOT NULL ORDER BY pod ASC");
    $used_pods = [];
    while ($row = $pod_stmt->fetch(PDO::FETCH_ASSOC)) {
        $used_pods[] = (int)$row['pod'];
    }

    $assigned_pod = 0;
    while (in_array($assigned_pod, $used_pods)) {
        $assigned_pod++;
    }

    // PNETLab membutuhkan field:
    // offline = 1
    // user_status = 1 (Aktif)
    // expired_time = UNIX Timestamp untuk login checking
    $query = "INSERT INTO users (username, email, name, password, role, offline, user_status, pod, online_time, expired_time)
              VALUES (:username, :email, :name, :password, :role, 1, 1, :pod, :online_time, :expired_time)";

    $statement = $db->prepare($query);

    $statement->bindParam(':username', $new_username, PDO::PARAM_STR);
    $statement->bindParam(':email', $new_email, PDO::PARAM_STR);
    $statement->bindParam(':name', $new_name, PDO::PARAM_STR);
    $statement->bindParam(':password', $hashed_password, PDO::PARAM_STR);
    $statement->bindParam(':role', $new_role, PDO::PARAM_INT);
    $statement->bindParam(':pod', $assigned_pod, PDO::PARAM_INT);
    $statement->bindParam(':online_time', $time_now, PDO::PARAM_INT);

    // Bind parameter expired (Bisa NULL jika selamanya)
    if ($expired_time === null) {
        $statement->bindValue(':expired_time', null, PDO::PARAM_NULL);
    } else {
        $statement->bindParam(':expired_time', $expired_time, PDO::PARAM_INT);
    }

    $statement->execute();

    echo "=========================================\n";
    echo "BERHASIL!\n";
    echo "=========================================\n";
    echo "Username   : {$new_username}\n";
    echo "Password   : {$new_password}\n";
    echo "Role       : User\n";
    echo "Pod Number : {$assigned_pod}\n";
    if ($expire_days > 0) {
        echo "Expired    : " . date('Y-m-d H:i:s', $expired_time) . " ({$expire_days} hari)\n";
    } else {
        echo "Expired    : Selamanya (Tidak expired)\n";
    }
    echo "=========================================\n";

} catch (PDOException $e) {
    echo "ERROR Database: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
?>
