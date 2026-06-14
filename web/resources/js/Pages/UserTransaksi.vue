<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    transactions: {
        type: Array,
        default: () => []
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(price);
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
</script>

<template>
    <Head title="Riwayat Transaksi - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Riwayat Transaksi</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Pantau seluruh riwayat pembelian voucher Anda di sini.</p>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto font-['Roboto']">
            
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Daftar Transaksi</h3>
                </div>
                <div class="overflow-x-auto" v-if="transactions.length > 0">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">ID Transaksi</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Paket Voucher</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            <tr v-for="trx in transactions" :key="trx.id" class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F] font-['Roboto_Mono']">
                                    #{{ trx.order_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[14px] font-[500] text-[#0F0F0F]">{{ trx.product ? trx.product.name : 'Unknown Product' }}</p>
                                    <p class="text-[12px] text-[#606060]">Akses {{ trx.product ? trx.product.duration_days : 0 }} Hari</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    {{ formatDate(trx.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F]">
                                    {{ formatPrice(trx.gross_amount) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="trx.status === 'success'" class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20">
                                        Berhasil
                                    </span>
                                    <span v-else-if="trx.status === 'pending'" class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#fff8e1] text-[#FB8C00] border border-[#FB8C00]/20">
                                        Menunggu Pembayaran
                                    </span>
                                    <span v-else class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#fce8e6] text-[#c5221f] border border-[#c5221f]/20">
                                        Gagal/Expired
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-else class="p-10 text-center">
                    <p class="text-[14px] text-[#606060] italic">Belum ada riwayat transaksi.</p>
                </div>
            </div>

            <div class="mt-8 text-center bg-[#F8F8F8] border border-[#E5E5E5] rounded-[12px] p-8">
                <p class="text-[16px] font-[500] text-[#0F0F0F]">Tertarik nambah wawasan?</p>
                <p class="text-[14px] text-[#606060] mt-1 mb-4">Yuk langganan paket voucher lainnya dan nikmati lab dengan performa maksimal.</p>
                <Link href="/#pricing" class="inline-block text-[14px] font-[500] text-[#FFFFFF] bg-[#BF070F] hover:bg-[#8F050A] px-6 py-2.5 rounded-[9999px] transition-colors shadow-sm">Lihat Paket Tersedia</Link>
            </div>

        </div>

    </AuthenticatedLayout>
</template>
