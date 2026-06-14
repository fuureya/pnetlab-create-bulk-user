<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: {
        type: Object,
        default: () => ({
            total_transactions: 0,
            active_vouchers: 0,
            expired_vouchers: 0
        })
    }
});
</script>

<template>
    <Head title="Users Overview - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Admin Dashboard -->
        <div v-if="$page.props.auth.user.role === 'admin'">
            <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Users Overview</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Manage Meraki Labs users, pods, and access expiration.</p>
            </div>
            <button class="bg-[#065FD4] hover:bg-[#0056b3] text-white px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Create New User
            </button>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto">
            
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex items-center justify-between">
                    <div>
                        <p class="text-[14px] font-[500] text-[#606060] mb-1">Total Users</p>
                        <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">1,248</h2>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#F2F2F2] flex items-center justify-center text-[#065FD4]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex items-center justify-between">
                    <div>
                        <p class="text-[14px] font-[500] text-[#606060] mb-1">Active Pods</p>
                        <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">14</h2>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#F2F2F2] flex items-center justify-center text-[#2BA640]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex items-center justify-between">
                    <div>
                        <p class="text-[14px] font-[500] text-[#606060] mb-1">Expiring Soon</p>
                        <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">8</h2>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#F2F2F2] flex items-center justify-center text-[#FF0000]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
            </div>

            <!-- Table Section -->
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Recent Users</h3>
                    <div class="relative">
                        <input type="text" placeholder="Search users..." class="h-[36px] pl-10 pr-4 border border-[#E5E5E5] rounded-[9999px] text-[14px] focus:outline-none focus:border-[#065FD4] w-[250px]">
                        <svg class="w-4 h-4 text-[#606060] absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Pod ID</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Expired At</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            
                            <tr class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#065FD4] text-white flex items-center justify-center text-[12px] font-[500]">W</div>
                                        <div>
                                            <p class="text-[14px] font-[500] text-[#0F0F0F]">Wahidah Gaming</p>
                                            <p class="text-[12px] text-[#606060]">wahidah_user</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F] font-['Roboto_Mono']">
                                    0
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    User
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    2026-06-15 12:00
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-1.5 text-[#606060] hover:bg-[#E5E5E5] rounded-[4px] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#2BA640] text-white flex items-center justify-center text-[12px] font-[500]">T</div>
                                        <div>
                                            <p class="text-[14px] font-[500] text-[#0F0F0F]">Test User</p>
                                            <p class="text-[12px] text-[#606060]">test_user_1</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F] font-['Roboto_Mono']">
                                    1
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    User
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    Never
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-1.5 text-[#606060] hover:bg-[#E5E5E5] rounded-[4px] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                    </button>
                                </td>
                            </tr>

                            <tr class="hover:bg-[#F8F8F8] transition-colors bg-[#FEF2F2]/30">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#606060] text-white flex items-center justify-center text-[12px] font-[500]">S</div>
                                        <div>
                                            <p class="text-[14px] font-[500] text-[#0F0F0F]">Student A</p>
                                            <p class="text-[12px] text-[#606060]">student_a</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F] font-['Roboto_Mono']">
                                    2
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    User
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#FEF2F2] text-[#FF0000] border border-[#FF0000]/20">
                                        Expired
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#FF0000]">
                                    2026-06-07 10:00
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="p-1.5 text-[#606060] hover:bg-[#E5E5E5] rounded-[4px] transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
                                    </button>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            </div>
        </div>

        <!-- User Dashboard -->
        <div v-else class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto font-['Roboto']">
            <div class="mb-8">
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Selamat Datang, {{ $page.props.auth.user.name }}!</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Kelola transaksi dan voucher Anda di sini.</p>
            </div>

            <!-- User KPI Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex flex-col justify-between">
                    <p class="text-[14px] font-[500] text-[#606060] mb-2">Total Transaksi</p>
                    <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">{{ stats.total_transactions }}</h2>
                </div>
                <!-- Card 2 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex flex-col justify-between">
                    <p class="text-[14px] font-[500] text-[#606060] mb-2">Voucher Aktif</p>
                    <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">{{ stats.active_vouchers }}</h2>
                </div>
                <!-- Card 3 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex flex-col justify-between">
                    <p class="text-[14px] font-[500] text-[#606060] mb-2">Voucher Expired</p>
                    <h2 class="text-[32px] font-[700] text-[#0F0F0F] leading-none">{{ stats.expired_vouchers }}</h2>
                </div>
                <!-- Card 4 -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] p-6 flex flex-col justify-between items-start">
                    <p class="text-[14px] font-[500] text-[#606060] mb-2">Profil Akun</p>
                    <a :href="route('profile.edit')" class="text-[14px] text-[#065FD4] font-[500] hover:underline mt-2 flex items-center gap-1">
                        Edit Profile
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            </div>

            <!-- Sections Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Transaction History -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#E5E5E5] bg-[#F8F8F8]">
                        <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Riwayat Transaksi</h3>
                    </div>
                    <div class="p-8 text-center flex flex-col items-center justify-center min-h-[250px]">
                        <div v-if="stats.total_transactions > 0">
                            <p class="text-[14px] text-[#0F0F0F] font-[500] mb-2">Anda memiliki {{ stats.total_transactions }} riwayat transaksi.</p>
                            <Link href="/riwayat-transaksi" class="text-[14px] text-[#065FD4] font-[500] hover:underline">Lihat Selengkapnya &rarr;</Link>
                        </div>
                        <div v-else>
                            <svg class="w-12 h-12 text-[#E5E5E5] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <p class="text-[14px] text-[#606060] font-[400]">Belum ada transaksi pembelian voucher.</p>
                            <a href="/#pricing" class="inline-flex mt-4 text-[14px] font-[500] text-[#FFFFFF] bg-[#065FD4] hover:bg-[#0056b3] px-5 py-2.5 rounded-[9999px] transition-colors items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                Beli Voucher
                            </a>
                        </div>
                    </div>
                </div>

                <!-- My Vouchers -->
                <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#E5E5E5] bg-[#F8F8F8]">
                        <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Voucher Saya</h3>
                    </div>
                    <div class="p-8 text-center flex flex-col items-center justify-center min-h-[250px]">
                        <svg class="w-12 h-12 text-[#E5E5E5] mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        <p class="text-[14px] text-[#606060] font-[400]">Anda belum memiliki voucher aktif.</p>
                        <a href="/aktivasi-voucher" class="inline-flex mt-4 text-[14px] font-[500] text-[#FFFFFF] bg-[#BF070F] hover:bg-[#8F050A] px-5 py-2.5 rounded-[9999px] transition-colors items-center gap-2">
                            Aktivasi Voucher
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>

    </AuthenticatedLayout>
</template>
