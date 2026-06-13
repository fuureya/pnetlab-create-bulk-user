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
                    <img src="/img/logo.png" alt="Meraki Labs" class="h-10">
                </div>
            </Link>
        </div>

        <div
            class="mt-8 w-full bg-[#FFFFFF] px-10 py-10 sm:max-w-md border border-[#E5E5E5] rounded-[8px] shadow-[0_8px_40px_rgba(0,0,0,0.1)]"
        >
            <h2 class="text-[24px] font-[700] text-[#0F0F0F] mb-2 text-center">Sign in</h2>
            <p class="text-[14px] text-[#606060] mb-8 text-center font-[400]">to continue to Meraki Labs</p>
            <slot />
        </div>
    </div>
</template>
