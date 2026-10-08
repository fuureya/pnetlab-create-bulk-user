<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarExpanded = ref(true);
</script>

<template>
    <div class="flex flex-col min-h-screen bg-[#F9FAFB] font-sans text-gray-800 selection:bg-shop-primary selection:text-white">
        
        <!-- Topbar -->
        <header class="h-16 bg-white/95 backdrop-blur-md flex items-center justify-between px-4 sm:px-6 sticky top-0 z-50 border-b border-gray-200/80 shadow-xs">
            <!-- Left: Toggle & Logo -->
            <div class="flex items-center gap-3 sm:gap-4">
                <button 
                    @click="sidebarExpanded = !sidebarExpanded" 
                    class="p-2 rounded-xl hover:bg-gray-100 text-gray-600 hover:text-gray-900 transition-colors focus:outline-none"
                    aria-label="Toggle Sidebar"
                >
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>
                
                <Link :href="route('dashboard')" class="flex items-center gap-2 group" title="Meraki Labs Dashboard">
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-8 md:h-9 transition-transform group-hover:scale-105">
                </Link>
            </div>
            
            <!-- Right Actions & User Menu -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Role Badge -->
                <span 
                    v-if="$page.props.auth.user.role === 'admin'"
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-shop-primary/10 text-shop-primary border border-shop-primary/20"
                >
                    <i class="fa-solid fa-shield-halved text-[11px]"></i>
                    <span>Administrator</span>
                </span>
                <span 
                    v-else
                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200"
                >
                    <i class="fa-solid fa-circle-check text-[11px]"></i>
                    <span>Member Lab</span>
                </span>

                <!-- Quick Landing Page Link -->
                <Link 
                    href="/" 
                    target="_blank"
                    class="p-2 rounded-xl text-gray-500 hover:text-shop-primary hover:bg-gray-100 transition-colors"
                    title="Buka Website Landing Page"
                >
                    <i class="fa-solid fa-arrow-up-right-from-square text-sm"></i>
                </Link>

                <!-- Profile Dropdown -->
                <Dropdown align="right" width="60">
                    <template #trigger>
                        <button class="flex items-center gap-2 p-1 rounded-full hover:ring-2 hover:ring-shop-primary/20 transition-all focus:outline-none">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-shop-primary to-rose-600 text-white flex items-center justify-center text-sm font-bold shadow-xs">
                                {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                            </div>
                        </button>
                    </template>
                    <template #content>
                        <div class="py-1 min-w-[260px] bg-white rounded-xl shadow-shop-lg border border-gray-100 divide-y divide-gray-100">
                            <!-- User Brief Header -->
                            <div class="px-4 py-3">
                                <p class="text-sm font-bold text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                                <span class="inline-block mt-1.5 px-2 py-0.5 rounded text-[10px] font-mono font-bold uppercase" :class="$page.props.auth.user.role === 'admin' ? 'bg-shop-primary/10 text-shop-primary' : 'bg-gray-100 text-gray-600'">
                                    {{ $page.props.auth.user.role }}
                                </span>
                            </div>

                            <!-- Links -->
                            <div class="py-1">
                                <Link 
                                    :href="route('profile.edit')" 
                                    class="w-full text-left px-4 py-2.5 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-shop-primary flex items-center gap-2.5 transition-colors"
                                >
                                    <i class="fa-solid fa-user-gear text-gray-400 text-sm"></i>
                                    <span>Pengaturan Profil</span>
                                </Link>
                                <Link 
                                    href="/aktivasi-voucher" 
                                    class="w-full text-left px-4 py-2.5 text-xs sm:text-sm text-gray-700 hover:bg-gray-50 hover:text-shop-primary flex items-center gap-2.5 transition-colors"
                                >
                                    <i class="fa-solid fa-bolt text-shop-primary text-sm"></i>
                                    <span>Aktivasi Voucher</span>
                                </Link>
                            </div>

                            <!-- Logout -->
                            <div class="py-1">
                                <DropdownLink 
                                    :href="route('logout')" 
                                    method="post" 
                                    as="button" 
                                    class="w-full text-left px-4 py-2.5 text-xs sm:text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2.5 transition-colors font-semibold"
                                >
                                    <i class="fa-solid fa-arrow-right-from-bracket text-sm"></i>
                                    <span>Keluar (Logout)</span>
                                </DropdownLink>
                            </div>
                        </div>
                    </template>
                </Dropdown>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside 
                :class="sidebarExpanded ? 'w-60' : 'w-20'" 
                class="hidden sm:flex flex-col justify-between shrink-0 bg-white border-r border-gray-200/80 transition-all duration-300 sticky top-16 h-[calc(100vh-64px)] z-20"
            >
                <div class="p-3 space-y-1">
                    <!-- Nav Item: Overview -->
                    <Link 
                        :href="route('dashboard')" 
                        class="flex items-center px-3.5 h-11 rounded-xl transition-all group"
                        :class="route().current('dashboard') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                        title="Overview"
                    >
                        <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                            <i class="fa-solid fa-gauge-high text-base"></i>
                        </div>
                        <span v-if="sidebarExpanded" class="text-sm truncate">Overview</span>
                    </Link>

                    <!-- User Only Links -->
                    <template v-if="$page.props.auth.user.role === 'user'">
                        <Link 
                            href="/riwayat-transaksi" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/riwayat-transaksi') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Riwayat Transaksi"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-receipt text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Transaksi</span>
                        </Link>

                        <Link 
                            href="/aktivasi-voucher" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/aktivasi-voucher') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Aktivasi Voucher"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-bolt text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Aktivasi Voucher</span>
                        </Link>
                    </template>

                    <!-- Admin Only Links -->
                    <template v-if="$page.props.auth.user.role === 'admin'">
                        <div v-if="sidebarExpanded" class="pt-4 pb-1 px-3">
                            <span class="text-[11px] font-mono uppercase tracking-wider text-gray-400 font-bold">Admin Modules</span>
                        </div>

                        <!-- Users / Voucher Lab -->
                        <Link 
                            :href="route('users')" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="route().current('users') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Manajemen Voucher Lab"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-ticket text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Voucher Lab</span>
                        </Link>

                        <!-- Produk -->
                        <Link 
                            href="/produk" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/produk') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Paket Produk"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-box-archive text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Produk Paket</span>
                        </Link>

                        <!-- Transaksi -->
                        <Link 
                            href="/transaksi" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/transaksi') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Transaksi Pengguna"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-file-invoice-dollar text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Transaksi</span>
                        </Link>

                        <!-- Testimoni -->
                        <Link 
                            href="/testimoni" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/testimoni') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Testimoni"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-comment-dots text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Testimoni</span>
                        </Link>

                        <!-- Pendaftar Akun -->
                        <Link 
                            href="/pendaftar" 
                            class="flex items-center px-3.5 h-11 rounded-xl transition-all"
                            :class="$page.url.startsWith('/pendaftar') ? 'bg-shop-primary text-white font-bold shadow-shop-md' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 font-medium'"
                            title="Pendaftar Akun Web"
                        >
                            <div class="flex items-center justify-center w-6 shrink-0" :class="sidebarExpanded ? 'mr-3' : 'mx-auto'">
                                <i class="fa-solid fa-users text-base"></i>
                            </div>
                            <span v-if="sidebarExpanded" class="text-sm truncate">Pendaftar Akun</span>
                        </Link>
                    </template>
                </div>

                <!-- Bottom Sidebar Cluster Status (Shown when expanded) -->
                <div v-if="sidebarExpanded" class="p-3.5 m-3 rounded-2xl bg-gray-50 border border-gray-200/80 text-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="font-bold text-gray-700">Simulator Core</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    </div>
                    <p class="text-[11px] text-gray-500 font-mono">PNETLab Cluster Active</p>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 bg-[#F9FAFB] overflow-x-hidden p-4 sm:p-6 lg:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
