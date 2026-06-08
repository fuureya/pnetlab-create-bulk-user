<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FFFFFF] pt-12 sm:justify-center sm:pt-0 font-['Roboto']"
    >
        <div>
            <Link href="/">
                <div class="flex items-center gap-1 p-4">
                    <svg class="w-8 h-8 text-[#FF0000]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                    </svg>
                    <h1 class="text-[24px] font-[700] text-[#0F0F0F] tracking-tighter ml-1">
                        RedBroadcast
                    </h1>
                </div>
            </Link>
        </div>

        <div
            class="mt-8 w-full bg-[#FFFFFF] px-10 py-10 sm:max-w-md border border-[#E5E5E5] rounded-[8px] shadow-[0_8px_40px_rgba(0,0,0,0.1)]"
        >
            <h2 class="text-[24px] font-[700] text-[#0F0F0F] mb-2 text-center">Sign in</h2>
            <p class="text-[14px] text-[#606060] mb-8 text-center font-[400]">to continue to RedBroadcast</p>
            <slot />
        </div>
    </div>
</template>
