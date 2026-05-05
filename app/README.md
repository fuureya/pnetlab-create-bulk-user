# PNetLab Bulk User Creator API (Secure PHP Native)

API ini dirancang untuk mengelola pembuatan user PNetLab secara massal melalui sistem pihak ketiga dengan standar keamanan tinggi.

## 🔒 Keamanan (Security)
API ini dilindungi menggunakan **API Key Authentication**. Setiap permintaan wajib menyertakan API Key pada Header HTTP.

- **Header Name**: `X-API-KEY`
- **Header Value**: Ambil dari file `.env` (Variable `API_KEY`)

## 🚀 Instalasi & Setup
1. Salin `.env.example` menjadi `.env`.
2. Generate API Key aman: `php -r "echo bin2hex(random_bytes(32));"`.
3. Masukkan Key tersebut ke `API_KEY` di file `.env`.
4. Sesuaikan konfigurasi database di `.env`.

## 🛠 Endpoint API

### 1. List Users
Mengambil semua data user PNetLab.
- **Method**: `GET`
- **URL**: `/bulkuser/api/v2/users`
- **Auth**: Required Header `X-API-KEY`

### 2. Create User
Membuat user baru. Sistem akan otomatis mencari nomor POD terkecil yang tersedia.
- **Method**: `POST`
- **URL**: `/bulkuser/api/v2/users`
- **Auth**: Required Header `X-API-KEY`
- **Body (JSON)**:
```json
{
  "username": "wahidah_user",
  "password": "wahidahgaming12345",
  "name": "Wahidah Gaming",
  "expired_days": 7
}
```
*Catatan: `expired_days` diisi angka hari. Jika tidak diisi, akun bersifat selamanya.*

### 3. Delete User
Menghapus user berdasarkan ID database.
- **Method**: `DELETE`
- **URL**: `/bulkuser/api/v2/users/{id}`
- **Auth**: Required Header `X-API-KEY`

## 🛡 Fitur Teknis
- **Anti SQL Injection**: Menggunakan PDO Prepared Statements.
- **SHA256 Hashing**: Password otomatis di-hash menggunakan SHA256 sesuai standar database PNetLab.
- **Auto POD Assignment**: Tidak perlu input nomor POD manual, sistem akan mencari yang kosong.
- **Native Efficiency**: Sangat ringan dan cepat karena tanpa framework berat.

---
*Dibuat dengan ❤️ untuk keamanan sistem PNetLab Anda.*
