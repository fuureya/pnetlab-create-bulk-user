<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Andi', 'role' => 'Network Engineer', 'content' => 'Lab sangat membantu untuk persiapan sertifikasi CCNA. Tinggal login dan langsung praktik tanpa harus pusing memikirkan spek PC.', 'color_theme' => 'primary'],
            ['name' => 'Rizky', 'role' => 'Mahasiswa Teknik Informatika', 'content' => 'Lebih hemat dibanding membeli perangkat fisik. Sangat direkomendasikan untuk mahasiswa dan pemula yang baru belajar jaringan.', 'color_theme' => 'secondary'],
            ['name' => 'Dimas', 'role' => 'System Administrator', 'content' => 'Server stabil dan support sangat responsif ketika ada pertanyaan atau kendala teknis di lapangan. Top banget!', 'color_theme' => 'info'],
            ['name' => 'Budi Santoso', 'role' => 'IT Support', 'content' => 'Saya berhasil lulus ujian MTCRE berkat latihan intensif di lab ini. Topologinya bisa dibuat sebebas mungkin sesuai keinginan.', 'color_theme' => 'success'],
            ['name' => 'Sari Indah', 'role' => 'Guru SMK TKJ', 'content' => 'Sangat membantu siswa kami dalam memahami materi routing dan switching karena tidak perlu membeli router mikrotik fisik dalam jumlah banyak.', 'color_theme' => 'warning'],
            ['name' => 'Reza Pahlevi', 'role' => 'DevOps Engineer', 'content' => 'Integrasi docker di dalam lab PNETLab sangat mempermudah saya menguji pipeline CI/CD sebelum deployment ke production.', 'color_theme' => 'primary'],
            ['name' => 'Agus Pratama', 'role' => 'Freelance IT', 'content' => 'Bisa diakses dari mana saja pakai tablet. Saat di cafe pun saya masih bisa nyeting topologi Cisco Nexus dengan lancar.', 'color_theme' => 'secondary'],
            ['name' => 'Fitriani', 'role' => 'Network Analyst', 'content' => 'Layanan cloud lab terbaik yang pernah saya coba. Latency-nya kecil banget jadi berasa nge-lab di server lokal.', 'color_theme' => 'info'],
            ['name' => 'Fajar Sidik', 'role' => 'Cyber Security Enthusiast', 'content' => 'Cocok buat pentesting dan nyobain berbagai teknik network attack & defense tanpa takut merusak jaringan asli.', 'color_theme' => 'success'],
            ['name' => 'Wahyu Hidayat', 'role' => 'ISP Engineer', 'content' => 'Fitur BGP dan OSPF simulatornya berjalan sangat akurat. Saya pakai untuk mereplika topologi jaringan ISP tempat saya bekerja.', 'color_theme' => 'warning'],
            ['name' => 'Dewi Sartika', 'role' => 'Junior Programmer', 'content' => 'Interface-nya sangat user friendly. Walaupun saya fokus di programming, belajar dasar jaringan jadi sangat menyenangkan.', 'color_theme' => 'primary'],
            ['name' => 'Rangga', 'role' => 'Network Consultant', 'content' => 'Biasanya saya kesulitan mendemokan topologi kompleks ke klien. Sekarang tinggal buka PNETLab ini dan semua jadi beres.', 'color_theme' => 'secondary'],
            ['name' => 'Nugroho', 'role' => 'IT Manager', 'content' => 'Sangat worth the price! Tim engineer kami skillnya meningkat pesat sejak kami berlangganan paket 4 minggu di sini.', 'color_theme' => 'info'],
            ['name' => 'Indra', 'role' => 'Cloud Architect', 'content' => 'Kemampuan untuk mensimulasikan lingkungan hybrid cloud dan on-premise dalam satu canvas sangat luar biasa.', 'color_theme' => 'success'],
            ['name' => 'Lestari', 'role' => 'Mahasiswa Ilmu Komputer', 'content' => 'Tugas akhir saya tentang SDN (Software Defined Network) berhasil saya selesaikan tepat waktu berkat PNETLab ini.', 'color_theme' => 'warning'],
            ['name' => 'Taufiq', 'role' => 'Cisco Instructor', 'content' => 'Image Cisco IOS yang disediakan sangat lengkap dan up-to-date. Sangat menunjang materi kursus yang saya bawakan.', 'color_theme' => 'primary'],
            ['name' => 'Hendra', 'role' => 'IT Staff', 'content' => 'Support WhatsApp-nya responsif parah! Tengah malam nanya pun kadang masih dibalas. Mantap pelayanannya.', 'color_theme' => 'secondary'],
            ['name' => 'Siska', 'role' => 'Network Technician', 'content' => 'Belajar Juniper JunOS jadi gampang karena image-nya sudah terinstal semua. Tinggal drag and drop, nyalakan, lalu konfigurasi.', 'color_theme' => 'info'],
            ['name' => 'Ferry', 'role' => 'Wireless Engineer', 'content' => 'Meski fokusnya ke jaringan kabel dan routing, lab ini tetap asik dipakai untuk simulasi controller wireless dasar.', 'color_theme' => 'success'],
            ['name' => 'Galih', 'role' => 'Security Engineer', 'content' => 'Mencoba integrasi Fortigate dan Palo Alto firewall sangat smooth di sini. Tidak memakan RAM laptop sama sekali karena full cloud.', 'color_theme' => 'warning']
        ];

        foreach ($testimonials as $testi) {
            Testimonial::create($testi);
        }
    }
}
