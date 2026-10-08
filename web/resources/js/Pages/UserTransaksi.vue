<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
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
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(price || 0);
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
    const totalSpent = props.transactions
        .filter(t => t.status === 'success')
        .reduce((acc, curr) => acc + (Number(curr.gross_amount) || 0), 0);
    const successCount = props.transactions.filter(t => t.status === 'success').length;
    const pendingCount = props.transactions.filter(t => t.status === 'pending').length;

    return { totalSpent, successCount, pendingCount };
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
            (t.product && t.product.name && t.product.name.toLowerCase().includes(q))
        );
    }
    return list;
});

const openDetailModal = (trx) => {
    selectedTransaction.value = trx;
    isDetailModalOpen.value = true;
};

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    selectedTransaction.value = null;
};

const copyInvoiceDetail = () => {
    if (!selectedTransaction.value) return;
    const t = selectedTransaction.value;
    const text = `INVOICE MERAKI LABS\nOrder ID: #${t.order_id}\nPaket: ${t.product ? t.product.name : 'Voucher Lab'}\nTotal: ${formatPrice(t.gross_amount)}\nStatus: ${t.status.toUpperCase()}\nTanggal: ${formatDate(t.created_at)}`;
    navigator.clipboard.writeText(text);
    Swal.fire({
        title: 'Berhasil Disalin!',
        text: 'Rincian transaksi telah disalin ke clipboard.',
        icon: 'success',
        timer: 1600,
        showConfirmButton: false
    });
};

const payTransaction = (token) => {
    if (!token) return;
    const isProd = Boolean(page.props.midtrans_is_production);
    const baseUrl = isProd ? 'https://app.midtrans.com/snap/v2/vtweb/' : 'https://app.sandbox.midtrans.com/snap/v2/vtweb/';
    window.location.href = baseUrl + token;
};
</script>

<template>
    <Head title="Riwayat Pembayaran - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Riwayat Transaksi Saya
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Pantau seluruh pembelian voucher lab, status pembayaran Midtrans, dan rincian tagihan.
                    </p>
                </div>
            </div>

            <Link 
                href="/#pricing" 
                class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg flex items-center gap-2"
            >
                <i class="fa-solid fa-cart-shopping text-xs"></i>
                <span>Beli Paket Baru</span>
            </Link>
        </div>

        <!-- KPI Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Pengeluaran</p>
                    <h3 class="text-2xl font-extrabold text-shop-primary leading-none">
                        {{ formatPrice(stats.totalSpent) }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Pembayaran tuntas</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Transaksi Berhasil</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 leading-none">
                        {{ stats.successCount }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Voucher lab aktif</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Menunggu Bayar</p>
                    <h3 class="text-2xl font-extrabold text-amber-600 leading-none">
                        {{ stats.pendingCount }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Menunggu checkout</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-clock"></i>
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
                    placeholder="Cari order ID atau paket..." 
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
                            <th class="px-6 py-3.5">Paket Voucher</th>
                            <th class="px-6 py-3.5">Waktu Pembelian</th>
                            <th class="px-6 py-3.5">Total Tagihan</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredTransactions.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-receipt"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Tidak ada riwayat transaksi</p>
                                    <p class="text-xs text-gray-400 mt-1">Anda belum memiliki transaksi dengan filter ini.</p>
                                </div>
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
                                <p class="font-bold text-gray-900">{{ trx.product ? trx.product.name : 'Voucher Lab' }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 font-mono">
                                    Durasi {{ trx.product ? trx.product.duration_days : '-' }} Hari
                                </p>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-xs text-gray-500">
                                {{ formatDate(trx.created_at) }}
                            </td>
                            <td class="px-6 py-3.5 font-mono font-extrabold text-shop-primary">
                                {{ formatPrice(trx.gross_amount) }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    v-if="trx.status === 'success'" 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200"
                                >
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    Berhasil
                                </span>
                                <div v-else-if="trx.status === 'pending'" class="flex flex-col gap-1.5 items-start">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        <i class="fa-solid fa-clock text-[10px]"></i>
                                        Menunggu Bayar
                                    </span>
                                </div>
                                <span 
                                    v-else 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200"
                                >
                                    <i class="fa-solid fa-ban text-[10px]"></i>
                                    Gagal / Expired
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <button 
                                        v-if="trx.status === 'pending'"
                                        @click="payTransaction(trx.snap_token)" 
                                        class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-3 py-1.5 rounded-lg text-xs font-bold shadow-xs transition-all flex items-center gap-1.5"
                                    >
                                        <i class="fa-solid fa-credit-card text-[10px]"></i>
                                        <span>Bayar</span>
                                    </button>

                                    <button 
                                        @click="openDetailModal(trx)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors"
                                        title="Lihat Detail Tagihan"
                                    >
                                        <i class="fa-solid fa-file-invoice text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Detail Modal / E-Invoice -->
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
                                Struk Transaksi Digital
                            </h2>
                            <p class="text-xs text-gray-500 font-mono">
                                #{{ selectedTransaction.order_id }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeDetailModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <!-- Invoice Content -->
                <div class="space-y-4">
                    <div class="bg-gray-50/70 p-4 rounded-xl border border-gray-100 space-y-3 text-xs">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status Transaksi:</span>
                            <span 
                                v-if="selectedTransaction.status === 'success'" 
                                class="font-bold text-emerald-700 flex items-center gap-1"
                            >
                                <i class="fa-solid fa-circle-check"></i> Sukses Terbayar
                            </span>
                            <span 
                                v-else-if="selectedTransaction.status === 'pending'" 
                                class="font-bold text-amber-700 flex items-center gap-1"
                            >
                                <i class="fa-solid fa-clock"></i> Menunggu Pembayaran
                            </span>
                            <span v-else class="font-bold text-rose-700 uppercase">
                                {{ selectedTransaction.status }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Paket Voucher:</span>
                            <span class="font-bold text-gray-900">{{ selectedTransaction.product ? selectedTransaction.product.name : 'Voucher Lab' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Durasi Akses:</span>
                            <span class="font-mono font-semibold text-gray-800">{{ selectedTransaction.product ? selectedTransaction.product.duration_days : '-' }} Hari PNETLab</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Waktu Pembelian:</span>
                            <span class="font-mono text-gray-800">{{ formatDate(selectedTransaction.created_at) }}</span>
                        </div>

                        <div class="pt-2 border-t border-gray-200 flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-800">Total Pembayaran:</span>
                            <span class="text-base font-extrabold text-shop-primary font-mono">
                                {{ formatPrice(selectedTransaction.gross_amount) }}
                            </span>
                        </div>
                    </div>

                    <!-- Payment Button if Pending -->
                    <div v-if="selectedTransaction.status === 'pending'" class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-xs">
                        <p class="font-bold text-amber-900 mb-2">Transaksi Belum Diselesaikan</p>
                        <p class="text-amber-800 mb-3">Klik tombol di bawah ini untuk membuka popup pembayaran resmi Midtrans.</p>
                        <button 
                            @click="payTransaction(selectedTransaction.snap_token)" 
                            class="w-full bg-shop-primary hover:bg-shop-secondary text-white font-bold py-2.5 rounded-xl transition-all shadow-xs flex items-center justify-center gap-2"
                        >
                            <i class="fa-solid fa-credit-card"></i>
                            <span>Bayar Sekarang via Midtrans</span>
                        </button>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-100">
                    <button 
                        @click="copyInvoiceDetail" 
                        class="text-xs font-semibold text-gray-600 hover:text-shop-primary flex items-center gap-1.5 transition-colors"
                    >
                        <i class="fa-solid fa-copy"></i>
                        <span>Salin Rincian</span>
                    </button>
                    <SecondaryButton @click="closeDetailModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Tutup</span>
                    </SecondaryButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
