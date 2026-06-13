<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
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
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Daftar Transaksi (Dummy)</h3>
                </div>
                <div class="overflow-x-auto">
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
                            
                            <!-- Dummy Row 1 -->
                            <tr class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F] font-['Roboto_Mono']">
                                    #TRX-829104
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[14px] font-[500] text-[#0F0F0F]">CCNA Labs Premium</p>
                                    <p class="text-[12px] text-[#606060]">Akses 30 Hari</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    10 Jun 2026, 14:30
                                </td>
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F]">
                                    Rp 150.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>

                            <!-- Dummy Row 2 -->
                            <tr class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F] font-['Roboto_Mono']">
                                    #TRX-829091
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[14px] font-[500] text-[#0F0F0F]">Paket Basic CCNP</p>
                                    <p class="text-[12px] text-[#606060]">Akses 7 Hari</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    01 Jun 2026, 09:15
                                </td>
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F]">
                                    Rp 50.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20">
                                        Berhasil
                                    </span>
                                </td>
                            </tr>

                            <!-- Dummy Row 3 -->
                            <tr class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F] font-['Roboto_Mono']">
                                    #TRX-828555
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[14px] font-[500] text-[#0F0F0F]">Lab Expert CCIE</p>
                                    <p class="text-[12px] text-[#606060]">Akses 3 Bulan</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    25 Mei 2026, 19:45
                                </td>
                                <td class="px-6 py-4 text-[14px] font-[500] text-[#0F0F0F]">
                                    Rp 400.000
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#fff8e1] text-[#FB8C00] border border-[#FB8C00]/20">
                                        Menunggu Pembayaran
                                    </span>
                                </td>
                            </tr>

                        </tbody>
                    </table>
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
