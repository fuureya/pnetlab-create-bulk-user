<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    admin_stats: {
        type: Object,
        default: () => ({
            total_vouchers: 0,
            active_vouchers: 0,
            unactive_vouchers: 0,
            expired_vouchers: 0,
            total_users: 0,
            total_transactions: 0,
            total_revenue: 0,
        })
    },
    stats: {
        type: Object,
        default: () => ({
            total_transactions: 0,
            active_vouchers: 0,
            expired_vouchers: 0
        })
    },
    recent_users: {
        type: Array,
        default: () => []
    },
    user_vouchers: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const copiedId = ref(null);

const filteredRecentUsers = computed(() => {
    if (!searchQuery.value) return props.recent_users;
    const q = searchQuery.value.toLowerCase();
    return props.recent_users.filter(v => 
        (v.username && v.username.toLowerCase().includes(q)) ||
        (v.status && v.status.toLowerCase().includes(q)) ||
        (v.pod_id && v.pod_id.toString().includes(q))
    );
});

const formatRupiah = (val) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val || 0);
};

const copyCredential = (text, id) => {
    navigator.clipboard.writeText(text);
    copiedId.value = id;
    setTimeout(() => {
        copiedId.value = null;
    }, 2000);
};
</script>

<template>
    <Head title="Dashboard - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- ADMIN DASHBOARD -->
        <div v-if="$page.props.auth.user.role === 'admin'" class="space-y-6">
            
            <!-- Header Toolbar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                        <i class="fa-solid fa-gauge-high"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                            Overview Lab & Statistik
                        </h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Monitoring ringkas penggunaan voucher, pod PNETLab, dan performa transaksi.
                        </p>
                    </div>
                </div>
                
                <div class="flex flex-wrap items-center gap-2.5">
                    <Link 
                        href="/transaksi" 
                        class="bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs flex items-center gap-2"
                    >
                        <i class="fa-solid fa-receipt text-xs text-gray-500"></i>
                        <span>Semua Transaksi</span>
                    </Link>
                    <Link 
                        :href="route('users')" 
                        class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg flex items-center gap-2"
                    >
                        <i class="fa-solid fa-ticket text-xs"></i>
                        <span>Kelola Voucher Lab</span>
                    </Link>
                </div>
            </div>

            <!-- KPI Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- Card 1: Total Voucher -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Voucher</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-none">
                            {{ admin_stats.total_vouchers }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">
                            <span class="text-emerald-600 font-bold">{{ admin_stats.active_vouchers }} aktif</span>, 
                            <span class="text-amber-600 font-bold">{{ admin_stats.unactive_vouchers }} belum</span>
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-ticket"></i>
                    </div>
                </div>

                <!-- Card 2: Voucher Aktif -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Aktif</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-emerald-600 leading-none">
                            {{ admin_stats.active_vouchers }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Sedang digunakan dalam lab</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <!-- Card 3: Total Pendaftar -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pendaftar</p>
                        <h3 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-none">
                            {{ admin_stats.total_users }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Akun pengguna terdaftar</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <!-- Card 4: Omset Transaksi -->
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Omset Berhasil</p>
                        <h3 class="text-xl sm:text-2xl font-extrabold text-shop-primary leading-none truncate max-w-[180px]">
                            {{ formatRupiah(admin_stats.total_revenue) }}
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-1.5 font-medium">
                            Dari {{ admin_stats.total_transactions }} total checkout
                        </p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                </div>
            </div>

            <!-- Recent Vouchers Table Section -->
            <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-gray-200/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Voucher & User Lab Terbaru</h3>
                        <p class="text-xs text-gray-500">Data voucher terakhir yang terdaftar di sistem.</p>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-60">
                            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari username / pod..." 
                                class="w-full h-9 pl-9 pr-3 rounded-xl border border-gray-200 bg-gray-50/50 text-xs focus:bg-white focus:border-shop-primary focus:ring-1 focus:ring-shop-primary transition-all"
                            />
                        </div>
                        <Link 
                            :href="route('users')" 
                            class="text-xs font-bold text-shop-primary hover:text-shop-secondary whitespace-nowrap px-3 py-2 rounded-xl hover:bg-shop-primary/10 transition-colors inline-flex items-center gap-1.5"
                        >
                            <span>Lihat Semua</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                                <th class="px-6 py-3.5">Username</th>
                                <th class="px-6 py-3.5">Password</th>
                                <th class="px-6 py-3.5">Pod ID</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5">Durasi</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="voucher in filteredRecentUsers" :key="voucher.id" class="hover:bg-gray-50/70 transition-colors">
                                <td class="px-6 py-3.5 font-mono font-bold text-gray-900">
                                    {{ voucher.username }}
                                </td>
                                <td class="px-6 py-3.5 font-mono text-gray-600">
                                    <div class="flex items-center gap-1.5">
                                        <span>{{ voucher.password || '-' }}</span>
                                        <button 
                                            v-if="voucher.password"
                                            @click="copyCredential(`User: ${voucher.username} | Pass: ${voucher.password}`, voucher.id)"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 transition-colors"
                                            title="Salin kredensial"
                                        >
                                            <i v-if="copiedId === voucher.id" class="fa-solid fa-check text-emerald-600"></i>
                                            <i v-else class="fa-solid fa-copy text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 font-mono text-gray-700">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-gray-100 font-bold text-xs border border-gray-200">Pod {{ voucher.pod_id }}</span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <span v-if="voucher.status === 'aktif'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Aktif
                                    </span>
                                    <span v-else-if="voucher.status === 'expired'" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Expired
                                    </span>
                                    <span v-else class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-circle text-[6px]"></i> Belum Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-3.5 text-gray-500 font-mono">
                                    {{ voucher.duration_days }} Hari
                                </td>
                                <td class="px-6 py-3.5 text-right font-medium">
                                    <Link :href="route('users')" class="text-shop-primary hover:text-shop-secondary font-bold text-xs inline-flex items-center gap-1">
                                        <span>Kelola</span>
                                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                    </Link>
                                </td>
                            </tr>
                            <tr v-if="filteredRecentUsers.length === 0">
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                    Tidak ada data voucher yang cocok.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- MEMBER / USER DASHBOARD -->
        <div v-else class="space-y-6">
            
            <!-- Welcome Header -->
            <div class="bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                        <i class="fa-solid fa-user-graduate"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                            Halo, {{ $page.props.auth.user.name }}
                        </h1>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Selamat datang di portal member Meraki Labs. Kelola voucher dan akses lab Anda di sini.
                        </p>
                    </div>
                </div>
                <Link 
                    href="/#pricing" 
                    class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-md shadow-shop-primary/20 hover:shadow-lg transition-all flex items-center gap-2"
                >
                    <i class="fa-solid fa-cart-shopping text-xs"></i>
                    <span>Beli Paket Baru</span>
                </Link>
            </div>

            <!-- User KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Transaksi</p>
                        <h3 class="text-2xl font-extrabold text-gray-900">{{ stats.total_transactions }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                </div>

                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Aktif</p>
                        <h3 class="text-2xl font-extrabold text-emerald-600">{{ stats.active_vouchers }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Voucher Expired</p>
                        <h3 class="text-2xl font-extrabold text-rose-600">{{ stats.expired_vouchers }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>
            </div>

            <!-- User Content: Voucher Saya & Transaksi -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Voucher Saya -->
                <div class="bg-white border border-gray-200/90 rounded-2xl overflow-hidden shadow-xs">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-900 text-sm">Voucher Lab Saya</h3>
                        <Link href="/aktivasi-voucher" class="text-xs font-bold text-shop-primary hover:underline flex items-center gap-1">
                            <span>Aktivasi</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>

                    <div v-if="user_vouchers && user_vouchers.length > 0" class="divide-y divide-gray-100">
                        <div v-for="v in user_vouchers" :key="v.id" class="p-4 sm:p-5 flex items-center justify-between hover:bg-gray-50/50 transition-colors">
                            <div>
                                <p class="font-mono font-bold text-gray-900 text-sm">{{ v.username }}</p>
                                <p class="font-mono text-xs text-gray-500 mt-0.5">Password: {{ v.password }}</p>
                                <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold" :class="v.status === 'aktif' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : (v.status === 'expired' ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200')">
                                    {{ v.status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Link 
                                    v-if="v.status === 'belum aktif'"
                                    href="/aktivasi-voucher" 
                                    class="text-xs font-bold bg-shop-primary hover:bg-shop-secondary text-white py-2 px-3.5 rounded-xl shadow-xs transition-colors"
                                >
                                    Aktivasi Sekarang
                                </Link>
                                <span v-else class="text-xs text-gray-500 font-mono font-bold bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">Pod {{ v.pod_id }}</span>
                            </div>
                        </div>
                    </div>
                    <div v-else class="p-8 text-center text-gray-400">
                        <i class="fa-solid fa-ticket text-3xl mb-2 text-gray-300"></i>
                        <p class="text-sm text-gray-600">Anda belum memiliki voucher lab.</p>
                        <Link href="/#pricing" class="inline-block mt-3 text-xs font-bold text-shop-primary hover:underline">
                            Pesan Paket Lab Sekarang &rarr;
                        </Link>
                    </div>
                </div>

                <!-- Riwayat Transaksi Ringkas -->
                <div class="bg-white border border-gray-200/90 rounded-2xl overflow-hidden shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Riwayat Pembayaran</h3>
                            <Link href="/riwayat-transaksi" class="text-xs font-bold text-shop-primary hover:underline flex items-center gap-1">
                                <span>Lihat Semua</span>
                                <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </Link>
                        </div>
                        <div class="p-6 text-center">
                            <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
                            <p class="text-sm text-gray-700 font-medium">
                                Total {{ stats.total_transactions }} transaksi tercatat.
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                Cek status pembayaran dan kode voucher otomatis di halaman riwayat.
                            </p>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50/70 border-t border-gray-100 text-center">
                        <Link href="/riwayat-transaksi" class="text-xs font-bold text-shop-primary hover:underline inline-flex items-center gap-1.5">
                            <span>Buka Daftar Transaksi</span>
                            <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </Link>
                    </div>
                </div>
            </div>

        </div>

    </AuthenticatedLayout>
</template>
