<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';

const page = usePage();
const showPassword = ref(false);

const form = useForm({
    username: '',
    password: '',
});

onMounted(() => {
    // Auto-fill username if passed in URL query (e.g., /aktivasi-voucher?username=labuser01)
    const urlParams = new URLSearchParams(window.location.search);
    const u = urlParams.get('username');
    if (u) {
        form.username = u;
    }
});

// Watch flash success
watch(() => page.props.flash?.success, (val) => {
    if (val) {
        Swal.fire({
            title: 'Aktivasi Berhasil!',
            text: val,
            icon: 'success',
            confirmButtonText: 'Buka Dashboard',
            confirmButtonColor: '#BF070F',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/dashboard';
            }
        });
    }
});

const submit = () => {
    Swal.fire({
        title: 'Mengaktifkan Voucher...',
        text: 'Menghubungkan akun ke server PNETLab',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    form.post(route('aktivasi.activate'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by flash watch or SweetAlert
            form.reset('password');
        },
        onError: () => {
            Swal.close();
        },
        onFinish: () => {
            if (!page.props.flash?.success) {
                Swal.close();
            }
        }
    });
};
</script>

<template>
    <Head title="Aktivasi Voucher Lab - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-key"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Aktivasi Voucher Lab
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Masukkan kredensial voucher Anda untuk memulai masa aktif dan membuka akses server PNETLab.
                    </p>
                </div>
            </div>

            <Link 
                href="/dashboard" 
                class="bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs flex items-center gap-2"
            >
                <i class="fa-solid fa-arrow-left text-xs text-gray-500"></i>
                <span>Kembali ke Dashboard</span>
            </Link>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left: Form Aktivasi (7 cols) -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-gray-200/80 p-6 sm:p-8 shadow-xs">
                <div class="border-b border-gray-100 pb-5 mb-6">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2.5">
                        <i class="fa-solid fa-shield-halved text-shop-primary"></i>
                        <span>Form Verifikasi Voucher</span>
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Pastikan Anda memasukkan username dan password persis seperti yang tertera di struk pembelian.
                    </p>
                </div>

                <!-- Flash Success Alert -->
                <div 
                    v-if="$page.props.flash && $page.props.flash.success" 
                    class="mb-6 p-4 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 flex items-start gap-3"
                >
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg mt-0.5 shrink-0"></i>
                    <div>
                        <p class="font-bold text-sm">{{ $page.props.flash.success }}</p>
                        <p class="text-xs text-emerald-700 mt-0.5">Akun pod PNETLab Anda telah aktif dan siap digunakan sekarang.</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <InputLabel for="username" value="Username Voucher" class="font-semibold text-xs mb-1" />
                        <div class="relative">
                            <TextInput
                                id="username"
                                type="text"
                                class="block w-full pl-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm font-mono"
                                v-model="form.username"
                                required
                                autofocus
                                placeholder="Contoh: labuser01"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-user text-xs"></i>
                            </div>
                        </div>
                        <InputError class="mt-1 text-xs" :message="form.errors.username" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password Lab" class="font-semibold text-xs mb-1" />
                        <div class="relative">
                            <TextInput
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                class="block w-full pl-10 pr-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm font-mono tracking-wider"
                                v-model="form.password"
                                required
                                placeholder="••••••••"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <i class="fa-solid fa-lock text-xs"></i>
                            </div>
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-700 transition-colors"
                            >
                                <i class="fa-solid text-xs" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <InputError class="mt-1 text-xs" :message="form.errors.password" />
                    </div>

                    <div class="pt-3">
                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] py-3.5 px-6 text-sm font-bold text-white shadow-md shadow-shop-primary/25 hover:shadow-lg transition-all disabled:opacity-50 disabled:pointer-events-none"
                            :disabled="form.processing"
                        >
                            <i class="fa-solid fa-bolt text-xs"></i>
                            <span v-if="form.processing">Memproses Aktivasi...</span>
                            <span v-else>Aktifkan Akses Lab Sekarang</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-5 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <i class="fa-solid fa-lock text-gray-400"></i>
                        Enkripsi API 256-Bit
                    </span>
                    <Link href="/riwayat-transaksi" class="text-shop-primary hover:underline font-semibold flex items-center gap-1">
                        <span>Lihat Kredensial di Transaksi</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </Link>
                </div>
            </div>

            <!-- Right: Panduan Aktivasi & Help (5 cols) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- 3 Langkah Aktivasi -->
                <div class="bg-white rounded-2xl border border-gray-200/80 p-6 shadow-xs">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-shop-primary"></i>
                        <span>Cara Aktivasi Voucher</span>
                    </h3>

                    <div class="space-y-4">
                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-shop-primary/10 text-shop-primary font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                1
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Salin Kredensial</h4>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                    Cek menu <Link href="/dashboard" class="text-shop-primary font-semibold underline">Dashboard</Link> atau riwayat transaksi untuk melihat username & password voucher Anda.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-shop-primary/10 text-shop-primary font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                2
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Input & Verifikasi</h4>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                    Masukkan kredensial pada formulir di sebelah kiri dan klik tombol <strong>Aktifkan Akses Lab</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3.5">
                            <div class="w-7 h-7 rounded-lg bg-shop-primary/10 text-shop-primary font-bold text-xs flex items-center justify-center shrink-0 mt-0.5">
                                3
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-900">Pod Langsung Siap</h4>
                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                                    Sistem akan menghubungkan akun ke server PNETLab dan durasi hari aktif mulai berjalan otomatis.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bantuan Cepat -->
                <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-2xl p-6 text-white shadow-xs">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-shop-primary text-lg mb-3">
                        <i class="fa-solid fa-headset text-white"></i>
                    </div>
                    <h3 class="text-base font-bold tracking-tight">Butuh Bantuan Aktivasi?</h3>
                    <p class="text-xs text-gray-300 mt-1 leading-relaxed">
                        Jika username voucher tidak ditemukan atau ada kendala koneksi ke server lab, tim support kami siap membantu 24/7.
                    </p>
                    <div class="mt-4 pt-4 border-t border-white/10 flex items-center justify-between">
                        <span class="text-xs text-gray-400">WhatsApp Support</span>
                        <a 
                            href="https://wa.me/6281234567890" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3 py-1.5 rounded-lg transition-colors"
                        >
                            <i class="fa-brands fa-whatsapp text-sm"></i>
                            <span>Hubungi CS</span>
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </AuthenticatedLayout>
</template>
