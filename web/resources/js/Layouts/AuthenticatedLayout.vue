<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const sidebarExpanded = ref(true);
</script>

<template>
    <div class="flex flex-col min-h-screen bg-[#FFFFFF] font-['Roboto'] text-[#0F0F0F]">
        
        <!-- Topbar (56px) -->
        <header class="h-[56px] bg-[#FFFFFF] flex items-center justify-between px-4 sticky top-0 z-50 shadow-[0_1px_2px_rgba(0,0,0,0.1)]">
            <!-- Left: Hamburger & Logo -->
            <div class="flex items-center gap-4">
                <button @click="sidebarExpanded = !sidebarExpanded" class="p-2 rounded-full hover:bg-[#F2F2F2] transition text-[#0F0F0F]">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <Link :href="route('dashboard')" class="flex items-center gap-1" title="Meraki Labs Home">
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-8 md:h-10">
                </Link>
            </div>
            
            <!-- Center: Spacer -->
            <div class="flex-1"></div>

            <!-- Right: User -->
            <div class="flex items-center gap-2">
                <div class="ml-2">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="flex items-center p-1 rounded-[9999px] hover:bg-[#F2F2F2] transition">
                                <div class="w-[36px] h-[36px] rounded-[9999px] bg-[#065FD4] text-white flex items-center justify-center text-[14px] font-[500]">
                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                </div>
                            </button>
                        </template>
                        <template #content>
                            <div class="py-2 w-[300px] shadow-[0_4px_32px_rgba(0,0,0,0.1)] rounded-[4px]">
                                <div class="px-4 py-3 flex items-start gap-4 border-b border-[#E5E5E5]">
                                    <div class="w-10 h-10 rounded-[9999px] bg-[#065FD4] text-white flex items-center justify-center text-[16px] font-[500] shrink-0">
                                        {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="text-[16px] font-[500] text-[#0F0F0F]">{{ $page.props.auth.user.name }}</p>
                                        <p class="text-[14px] font-[400] text-[#0F0F0F] mt-1">@{{ $page.props.auth.user.name.toLowerCase().replace(/\s+/g, '') }}</p>
                                        <Link :href="route('profile.edit')" class="text-[14px] text-[#065FD4] font-[500] mt-2 block hover:underline">View your channel</Link>
                                    </div>
                                </div>
                                <div class="py-2">
                                    <DropdownLink :href="route('logout')" method="post" as="button" class="px-4 py-2 hover:bg-[#F2F2F2] flex items-center gap-4 text-[14px] font-[400] text-[#0F0F0F]">
                                        <svg class="w-6 h-6 text-[#606060]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        Sign out
                                    </DropdownLink>
                                </div>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </header>

        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside 
                :class="sidebarExpanded ? 'w-[240px]' : 'w-[72px]'" 
                class="hidden sm:flex flex-col shrink-0 bg-[#FFFFFF] hover:overflow-y-auto overflow-hidden transition-all duration-200 sticky top-[56px] h-[calc(100vh-56px)]"
            >
                <div class="py-3 px-3 flex flex-col gap-1">
                    <Link :href="route('dashboard')" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="route().current('dashboard') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="route().current('dashboard')"><path d="M4 21V10.08l8-6.96 8 6.96V21h-6v-6h-4v6H4z"/></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path d="M4 21V10.08l8-6.96 8 6.96V21h-6v-6h-4v6H4z"/></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Overview</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Overview</span>
                    </Link>

                    <Link :href="route('users')" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="route().current('users') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="route().current('users')"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Users</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Users</span>
                    </Link>

                    <Link href="/produk" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="$page.url.startsWith('/produk') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="$page.url.startsWith('/produk')"><path d="M21 16.811c0 .864-.466 1.64-1.196 2.062l-6.804 3.931a2.38 2.38 0 01-2.001 0l-6.804-3.931A2.38 2.38 0 013 16.811V7.189c0-.864.466-1.64 1.196-2.062l6.804-3.931c.622-.36 1.379-.36 2.001 0l6.804 3.931A2.38 2.38 0 0121 7.189v9.622zM3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path stroke-linecap="round" stroke-linejoin="round" d="M21 16.811c0 .864-.466 1.64-1.196 2.062l-6.804 3.931a2.38 2.38 0 01-2.001 0l-6.804-3.931A2.38 2.38 0 013 16.811V7.189c0-.864.466-1.64 1.196-2.062l6.804-3.931c.622-.36 1.379-.36 2.001 0l6.804 3.931A2.38 2.38 0 0121 7.189v9.622z" /><path stroke-linecap="round" stroke-linejoin="round" d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Produk</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Produk</span>
                    </Link>

                    <Link href="/transaksi" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="$page.url.startsWith('/transaksi') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="$page.url.startsWith('/transaksi')"><path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Transaksi</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Transaksi</span>
                    </Link>

                    <Link href="/testimoni" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="$page.url.startsWith('/testimoni') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="$page.url.startsWith('/testimoni')"><path d="M12 2C6.486 2 2 5.589 2 10c0 2.908 1.897 5.515 5 7.022V22l5.064-2.25c.306.015.617.03 1.936.03 5.514 0 10-3.589 10-8s-4.486-8-10-8zm0 14c-1.127 0-2-.134-2.5-.236l-2.483 1.103.013-2.127c-2.023-1.077-3.03-2.883-3.03-4.74 0-3.309 3.589-6 8-6s8 2.691 8 6-3.589 6-8 6z"/></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Testimoni</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Testimoni</span>
                    </Link>

                    <Link href="/pendaftar" class="flex items-center px-[16px] h-[40px] rounded-[8px] transition-colors" :class="$page.url.startsWith('/pendaftar') ? 'bg-[#F2F2F2] font-[700]' : 'hover:bg-[#F2F2F2] font-[400]'">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" v-if="$page.url.startsWith('/pendaftar')"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" v-else><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Pendaftar</span>
                        <span v-else class="text-[10px] font-[400] absolute mt-10">Pendaftar</span>
                    </Link>

                    <Link href="#" class="flex items-center px-[16px] h-[40px] rounded-[8px] hover:bg-[#F2F2F2] transition-colors font-[400]">
                        <div class="flex items-center justify-center w-[24px] h-[24px] shrink-0" :class="sidebarExpanded ? 'mr-6' : 'mx-auto'">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        </div>
                        <span v-if="sidebarExpanded" class="text-[14px] truncate whitespace-nowrap">Create User</span>
                        <span v-else class="text-[10px] absolute mt-10">Create</span>
                    </Link>
                </div>

                <div class="border-t border-[#E5E5E5] py-3 px-3 flex flex-col gap-1" v-if="sidebarExpanded">
                    <div class="px-[16px] py-2 flex items-center font-[500] text-[16px]">
                        System
                    </div>
                    <Link href="#" class="flex items-center px-[16px] h-[40px] rounded-[8px] hover:bg-[#F2F2F2] transition-colors font-[400]">
                        <div class="flex items-center justify-center w-[24px] h-[24px] mr-6 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg></div>
                        <span class="text-[14px]">Settings</span>
                    </Link>
                    <Link href="#" class="flex items-center px-[16px] h-[40px] rounded-[8px] hover:bg-[#F2F2F2] transition-colors font-[400]">
                        <div class="flex items-center justify-center w-[24px] h-[24px] mr-6 shrink-0"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg></div>
                        <span class="text-[14px]">Servers</span>
                    </Link>
                </div>
            </aside>

            <!-- Main Content Area -->
            <main class="flex-1 bg-[#FFFFFF] overflow-x-hidden">
                <slot />
            </main>
        </div>
    </div>
</template>
