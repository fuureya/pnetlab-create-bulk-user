<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - PNETLab</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen py-12 px-4">
    <div class="max-w-xl mx-auto">
        <a href="index.php" class="text-slate-500 hover:text-slate-800 transition mb-6 inline-flex items-center text-sm font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
        
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden mt-2">
            <div class="bg-indigo-600 px-8 py-6 text-white">
                <h2 class="text-2xl font-bold">Tambah User Baru</h2>
                <p class="text-indigo-100 text-sm mt-1">Lengkapi form di bawah untuk membuat akun PNETLab baru.</p>
            </div>
            
            <form action="store.php" method="POST" class="p-8 space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" required placeholder="Contoh: labuser01" 
                            class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" required placeholder="Masukkan password" 
                            class="block w-full pl-10 pr-3 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap (Opsional)</label>
                    <input type="text" name="name" placeholder="PNETLab User" 
                        class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Batas Masa Aktif (Expired)</label>
                    <select name="expired" class="block w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition appearance-none bg-white">
                        <option value="0">Tidak Ada (Selamanya)</option>
                        <option value="1">1 Hari</option>
                        <option value="2">2 Hari</option>
                        <option value="3">3 Hari</option>
                        <option value="7">1 Minggu (7 Hari)</option>
                        <option value="30">1 Bulan (30 Hari)</option>
                    </select>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-100 transition duration-300 transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i> Buat Akun Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
