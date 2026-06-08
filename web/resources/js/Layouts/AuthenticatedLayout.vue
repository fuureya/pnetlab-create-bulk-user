<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="flex min-h-screen bg-[#F3F4F6] text-[#111827] font-['Inter']">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-[#E5E7EB] hidden md:flex flex-col shrink-0 z-20 relative">
            <!-- Sidebar Header -->
            <div class="h-20 flex items-center px-6 border-b border-[#E5E7EB]">
                <Link :href="route('dashboard')">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        <h1 class="text-xl font-semibold tracking-wide">
                            Watt<span class="text-[#06B6D4]">Vision</span>
                        </h1>
                    </div>
                </Link>
            </div>
            
            <!-- Sidebar Links -->
            <nav class="flex-1 py-6 flex flex-col gap-2 px-4">
                <Link 
                    :href="route('dashboard')" 
                    class="flex items-center px-4 py-3 rounded-lg transition-colors gap-3 w-full text-[14px]"
                    :class="route().current('dashboard') ? 'bg-[#F3F4F6] text-[#06B6D4] border-l-2 border-[#06B6D4] font-medium' : 'text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827]'"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                    Dashboard
                </Link>
                
                <Link 
                    href="#" 
                    class="flex items-center px-4 py-3 rounded-lg transition-colors gap-3 w-full text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827] text-[14px]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Consumo (kWh)
                </Link>
                
                <Link 
                    href="#" 
                    class="flex items-center px-4 py-3 rounded-lg transition-colors gap-3 w-full text-[#6B7280] hover:bg-[#F9FAFB] hover:text-[#111827] text-[14px]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                    Usuarios
                </Link>
            </nav>
            
            <div class="p-4 border-t border-[#E5E7EB]">
                 <div class="flex items-center gap-3">
                     <div class="w-2 h-2 rounded-full bg-[#22C55E] shadow-[0_0_8px_#22C55E]"></div>
                     <span class="text-[#6B7280] text-[12px] font-medium tracking-wider uppercase">System Online</span>
                 </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Topbar -->
            <header class="h-20 bg-white border-b border-[#E5E7EB] flex items-center justify-between px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex items-center md:hidden">
                    <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="p-2 rounded-md text-[#6B7280] hover:text-[#111827] hover:bg-[#F3F4F6] focus:outline-none transition">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                    <Link :href="route('dashboard')" class="ml-4 md:hidden">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                    </Link>
                </div>
                
                <div class="hidden md:flex flex-1">
                    <!-- Search bar placeholder -->
                    <div class="relative w-64">
                        <input type="text" class="w-full bg-[#F3F4F6] border border-[#E5E7EB] rounded-md px-4 py-2 text-[14px] text-[#111827] focus:border-[#06B6D4] focus:ring-1 focus:ring-[#06B6D4] placeholder-[#6B7280]" placeholder="Search metrics...">
                        <svg class="absolute right-3 top-2.5 w-4 h-4 text-[#6B7280]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="flex items-center">
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="inline-flex rounded-md cursor-pointer">
                                <span class="inline-flex items-center text-[14px] font-medium text-[#111827] transition ease-in-out duration-150">
                                    {{ $page.props.auth.user.name }}
                                    <div class="ml-3 w-8 h-8 bg-[#F3F4F6] rounded-full flex items-center justify-center border border-[#E5E7EB]">
                                        <svg class="w-4 h-4 text-[#06B6D4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    </div>
                                    <svg class="ml-2 -mr-0.5 h-4 w-4 text-[#6B7280]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        </template>

                        <template #content>
                            <div class="bg-white rounded-md border border-[#E5E7EB] shadow-md overflow-hidden">
                                <DropdownLink :href="route('profile.edit')" class="text-[#111827] hover:bg-[#F3F4F6] text-[14px]">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button" class="text-[#EF4444] hover:bg-[#F3F4F6] text-[14px]">
                                    Log Out
                                </DropdownLink>
                            </div>
                        </template>
                    </Dropdown>
                </div>
            </header>

            <!-- Mobile Navigation Menu -->
            <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="md:hidden bg-white border-b border-[#E5E7EB]">
                <div class="pt-2 pb-3 space-y-1">
                    <Link :href="route('dashboard')" class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium" :class="route().current('dashboard') ? 'border-[#06B6D4] text-[#06B6D4] bg-[#F3F4F6]' : 'border-transparent text-[#6B7280] hover:text-[#111827] hover:bg-[#F3F4F6]'">
                        Dashboard
                    </Link>
                    <Link href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#6B7280] hover:text-[#111827] hover:bg-[#F3F4F6]">
                        Consumo
                    </Link>
                    <Link href="#" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-[#6B7280] hover:text-[#111827] hover:bg-[#F3F4F6]">
                        Usuarios
                    </Link>
                </div>
            </div>

            <!-- Page Heading (Slot) -->
            <div class="px-8 py-6" v-if="$slots.header">
                <slot name="header" />
            </div>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto px-4 sm:px-6 lg:px-8 pb-12">
                <slot />
            </main>
        </div>
    </div>
</template>
