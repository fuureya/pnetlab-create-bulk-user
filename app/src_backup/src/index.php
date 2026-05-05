<?php
require 'config.php';

$stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PNETLab User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen">
    <div class="max-w-6xl mx-auto py-10 px-4">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">PNETLab User Management</h1>
                <p class="text-slate-500 mt-1">Kelola akun pengguna PNETLab Anda secara efisien.</p>
            </div>
            <a href="create.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition duration-200 flex items-center shadow-md shadow-indigo-200">
                <i class="fas fa-user-plus mr-2"></i> Tambah User Baru
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 text-sm font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Name / Email</th>
                            <th class="px-6 py-4 text-center">POD ID</th>
                            <th class="px-6 py-4 text-center">Role</th>
                            <th class="px-6 py-4">Status / Expired</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                <i class="fas fa-users-slash text-4xl mb-3 block opacity-20"></i>
                                Belum ada data user.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-slate-50 transition duration-150">
                                <td class="px-6 py-4 font-semibold text-slate-800"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-slate-700"><?= htmlspecialchars($user['name']) ?></div>
                                    <div class="text-xs text-slate-400"><?= htmlspecialchars($user['email']) ?></div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold leading-none text-indigo-700 bg-indigo-100 rounded-full">
                                        POD <?= $user['pod'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-slate-600">
                                    <?= $user['role'] == 0 ? '<span class="text-red-600 font-bold">Admin</span>' : 'User' ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($user['expired_time']): ?>
                                        <div class="text-sm text-slate-600">
                                            <?= date('Y-m-d H:i', $user['expired_time']) ?>
                                        </div>
                                        <?php if ($user['expired_time'] < time()): ?>
                                            <span class="text-[10px] bg-red-100 text-red-600 px-1.5 rounded font-bold uppercase">Expired</span>
                                        <?php else: ?>
                                            <span class="text-[10px] bg-green-100 text-green-600 px-1.5 rounded font-bold uppercase text-xs">Aktif</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-sm text-slate-400 italic">Selamanya</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right space-x-2">
                                    <a href="edit.php?id=<?= $user['id'] ?>" class="text-indigo-600 hover:text-indigo-900 transition font-medium text-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete.php?id=<?= $user['id'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')" class="text-red-500 hover:text-red-700 transition font-medium text-sm ml-3">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
