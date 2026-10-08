<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const statusFilter = ref('all');

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};

const stats = computed(() => {
    const totalRevenue = props.transactions
        .filter(t => t.status === 'success')
        .reduce((acc, curr) => acc + (Number(curr.gross_amount) || 0), 0);
    const successCount = props.transactions.filter(t => t.status === 'success').length;
    const pendingCount = props.transactions.filter(t => t.status === 'pending').length;
    const failedCount = props.transactions.filter(t => t.status !== 'success' && t.status !== 'pending').length;

    return { totalRevenue, successCount, pendingCount, failedCount };
});

const filteredTransactions = computed(() => {
    let list = props.transactions;
    if (statusFilter.value !== 'all') {
        if (statusFilter.value === 'failed') {
            list = list.filter(t => t.status !== 'success' && t.status !== 'pending');
        } else {
            list = list.filter(t => t.status === statusFilter.value);
        }
    }
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(t => 
            (t.order_id && t.order_id.toLowerCase().includes(q)) ||
            (t.user && t.user.name && t.user.name.toLowerCase().includes(q)) ||
            (t.user && t.user.email && t.user.email.toLowerCase().includes(q))
        );
    }
    return list;
});
</script>

<template>
    <Head title="Manajemen Transaksi - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div>
                <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                    Manajemen Transaksi Midtrans
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Pantau seluruh riwayat transaksi otomatis, status webhook, dan omset penjualan voucher.
                </p>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <!-- Total Omset -->
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Omset Sukses</p>
                    <h3 class="text-xl sm:text-2xl font-extrabold text-shop-primary leading-none">
                        {{ formatPrice(stats.totalRevenue) }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Pembayaran tuntas</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
            </div>

            <!-- Transaksi Berhasil -->
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Transaksi Berhasil</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 leading-none">
                        {{ stats.successCount }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Voucher terbit otomatis</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <!-- Transaksi Pending -->
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Menunggu Bayar</p>
                    <h3 class="text-2xl font-extrabold text-amber-600 leading-none">
                        {{ stats.pendingCount }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Menunggu transfer/QRIS</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </div>

            <!-- Transaksi Gagal / Expired -->
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Expired / Batal</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 leading-none">
                        {{ stats.failedCount }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Waktu bayar habis</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-ban"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-xs mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-1.5 w-full md:w-auto">
                <button 
                    @click="statusFilter = 'all'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'all' ? 'bg-shop-primary text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Semua Transaksi
                </button>
                <button 
                    @click="statusFilter = 'success'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'success' ? 'bg-emerald-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Berhasil
                </button>
                <button 
                    @click="statusFilter = 'pending'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'pending' ? 'bg-amber-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Menunggu
                </button>
                <button 
                    @click="statusFilter = 'failed'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'failed' ? 'bg-rose-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Gagal / Expired
                </button>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-gray-400"></i>
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Cari order ID atau nama..." 
                    class="w-full h-9 pl-9 pr-3 rounded-xl border border-gray-200 text-xs focus:border-shop-primary focus:ring-1 focus:ring-shop-primary"
                />
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5">ID Order</th>
                            <th class="px-6 py-3.5">Pengguna</th>
                            <th class="px-6 py-3.5">Paket Voucher</th>
                            <th class="px-6 py-3.5">Tanggal</th>
                            <th class="px-6 py-3.5">Total Harga</th>
                            <th class="px-6 py-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredTransactions.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-receipt text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm">Tidak ada transaksi yang cocok.</p>
                            </td>
                        </tr>
                        <tr 
                            v-for="trx in filteredTransactions" 
                            :key="trx.id" 
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono font-bold text-gray-900">
                                #{{ trx.order_id }}
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-bold text-gray-900">{{ trx.user ? trx.user.name : 'Unknown User' }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ trx.user ? trx.user.email : '-' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-bold text-gray-900">{{ trx.product ? trx.product.name : 'Unknown Product' }}</p>
                                <p class="text-xs text-gray-500">Durasi {{ trx.product ? trx.product.duration_days : 0 }} Hari</p>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-600 font-mono">
                                {{ formatDate(trx.created_at) }}
                            </td>
                            <td class="px-6 py-3.5 font-bold font-mono text-gray-900">
                                {{ formatPrice(trx.gross_amount) }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    v-if="trx.status === 'success'" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                >
                                    <i class="fa-solid fa-circle-check text-xs"></i>
                                    <span>Berhasil</span>
                                </span>
                                <span 
                                    v-else-if="trx.status === 'pending'" 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200"
                                >
                                    <i class="fa-solid fa-clock text-xs"></i>
                                    <span>Menunggu Bayar</span>
                                </span>
                                <span 
                                    v-else 
                                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200"
                                >
                                    <i class="fa-solid fa-ban text-xs"></i>
                                    <span>Batal / Expired</span>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
