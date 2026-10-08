<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const statusFilter = ref('all');
const isDetailModalOpen = ref(false);
const selectedTransaction = ref(null);

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price);
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
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
            (t.user && t.user.email && t.user.email.toLowerCase().includes(q)) ||
            (t.product && t.product.name && t.product.name.toLowerCase().includes(q))
        );
    }
    return list;
});

const openDetailModal = (transaction) => {
    selectedTransaction.value = transaction;
    isDetailModalOpen.value = true;
};

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    selectedTransaction.value = null;
};

const exportToCSV = () => {
    if (filteredTransactions.value.length === 0) {
        Swal.fire({ title: 'Data Kosong', text: 'Tidak ada data transaksi untuk diekspor.', icon: 'info' });
        return;
    }
    let csv = 'No,Order ID,Nama Pengguna,Email,Paket,Harga,Status,Tanggal\n';
    filteredTransactions.value.forEach((t, idx) => {
        csv += `"${idx + 1}","${t.order_id}","${t.user ? t.user.name : '-'}","${t.user ? t.user.email : '-'}","${t.product ? t.product.name : '-'}","${t.gross_amount}","${t.status}","${formatDate(t.created_at)}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `transaksi-meraki-${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};
</script>

<template>
    <Head title="Manajemen Transaksi - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Manajemen Transaksi Midtrans
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Pantau seluruh riwayat transaksi otomatis, status webhook, dan omset penjualan voucher.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <button 
                    @click="exportToCSV" 
                    class="bg-emerald-50 hover:bg-emerald-100 active:scale-[0.98] text-emerald-700 border border-emerald-200 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs flex items-center gap-2"
                    title="Export data ke file CSV"
                >
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </button>
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
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all"
                    :class="statusFilter === 'all' ? 'bg-shop-primary text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Semua Transaksi
                </button>
                <button 
                    @click="statusFilter = 'success'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'success' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    Berhasil
                </button>
                <button 
                    @click="statusFilter = 'pending'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'pending' ? 'bg-amber-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-clock text-[10px]"></i>
                    Menunggu
                </button>
                <button 
                    @click="statusFilter = 'failed'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'failed' ? 'bg-rose-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-ban text-[10px]"></i>
                    Gagal / Expired
                </button>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Cari order ID, nama, atau email..." 
                    class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 bg-gray-50/50 text-xs focus:bg-white focus:border-shop-primary focus:ring-2 focus:ring-shop-primary/20 transition-all"
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
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredTransactions.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Tidak ada riwayat transaksi</p>
                                    <p class="text-xs text-gray-400 mt-1">Belum ada transaksi dengan filter yang dipilih.</p>
                                </div>
                            </td>
                        </tr>
                        <tr 
                            v-for="transaction in filteredTransactions" 
                            :key="transaction.id"
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono font-bold text-gray-900">
                                {{ transaction.order_id }}
                            </td>
                            <td class="px-6 py-3.5">
                                <div v-if="transaction.user">
                                    <p class="font-bold text-gray-900">{{ transaction.user.name }}</p>
                                    <p class="text-xs text-gray-500">{{ transaction.user.email }}</p>
                                </div>
                                <span v-else class="text-gray-400 italic">User Terhapus</span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-700">
                                <span v-if="transaction.product" class="font-medium">
                                    {{ transaction.product.name }}
                                </span>
                                <span v-else class="text-gray-400">-</span>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-xs text-gray-500">
                                {{ formatDate(transaction.created_at) }}
                            </td>
                            <td class="px-6 py-3.5 font-mono font-extrabold text-shop-primary">
                                {{ formatPrice(transaction.gross_amount) }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    v-if="transaction.status === 'success'"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                >
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    Sukses
                                </span>
                                <span 
                                    v-else-if="transaction.status === 'pending'"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"
                                >
                                    <i class="fa-solid fa-clock text-[10px]"></i>
                                    Pending
                                </span>
                                <span 
                                    v-else
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200"
                                >
                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                    {{ transaction.status }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <button 
                                    @click="openDetailModal(transaction)" 
                                    class="w-8 h-8 rounded-xl inline-flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                    title="Lihat Detail Transaksi"
                                >
                                    <i class="fa-solid fa-file-invoice text-sm"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Modal -->
        <Modal :show="isDetailModalOpen" @close="closeDetailModal">
            <div class="p-6" v-if="selectedTransaction">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                Detail Transaksi
                            </h2>
                            <p class="text-xs text-gray-500 font-mono">
                                {{ selectedTransaction.order_id }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeDetailModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4 bg-gray-50/70 p-4 rounded-xl border border-gray-100 text-xs">
                        <div>
                            <span class="text-gray-400 block mb-0.5">Status Pembayaran</span>
                            <span 
                                v-if="selectedTransaction.status === 'success'" 
                                class="inline-flex items-center gap-1 font-bold text-emerald-700"
                            >
                                <i class="fa-solid fa-circle-check"></i> Sukses Terbayar
                            </span>
                            <span 
                                v-else-if="selectedTransaction.status === 'pending'" 
                                class="inline-flex items-center gap-1 font-bold text-amber-700"
                            >
                                <i class="fa-solid fa-clock"></i> Menunggu Pembayaran
                            </span>
                            <span v-else class="font-bold text-rose-700 uppercase">
                                {{ selectedTransaction.status }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 block mb-0.5">Total Tagihan</span>
                            <span class="font-extrabold text-shop-primary text-sm font-mono">
                                {{ formatPrice(selectedTransaction.gross_amount) }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 block mb-0.5">Waktu Transaksi</span>
                            <span class="font-semibold text-gray-800 font-mono">
                                {{ formatDate(selectedTransaction.created_at) }}
                            </span>
                        </div>

                        <div>
                            <span class="text-gray-400 block mb-0.5">Paket Layanan</span>
                            <span class="font-semibold text-gray-800">
                                {{ selectedTransaction.product ? selectedTransaction.product.name : 'Voucher PNETLab' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-4 bg-white rounded-xl border border-gray-200 text-xs space-y-2">
                        <p class="font-bold text-gray-900 border-b pb-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-user text-gray-400"></i> Informasi Pelanggan
                        </p>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nama:</span>
                            <span class="font-semibold text-gray-900">{{ selectedTransaction.user ? selectedTransaction.user.name : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email:</span>
                            <span class="font-mono text-gray-800">{{ selectedTransaction.user ? selectedTransaction.user.email : '-' }}</span>
                        </div>
                    </div>

                    <div v-if="selectedTransaction.snap_token" class="p-3.5 bg-blue-50/50 rounded-xl border border-blue-100 text-xs text-blue-900">
                        <span class="font-semibold block mb-1">Midtrans Snap Token:</span>
                        <code class="font-mono text-[11px] bg-white px-2 py-1 rounded border border-blue-200 block truncate">{{ selectedTransaction.snap_token }}</code>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <SecondaryButton @click="closeDetailModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Tutup</span>
                    </SecondaryButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
