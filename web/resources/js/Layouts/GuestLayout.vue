<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-50 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <h1 class="text-3xl font-bold text-blue-600 font-['Red_Hat_Display']">PropertyVue</h1>
            </Link>
        </div>

        <div
            class="mt-8 w-full overflow-hidden bg-white px-8 py-6 border border-gray-200 shadow-sm sm:max-w-md sm:rounded-lg font-['DM_Sans']"
        >
            <slot />
        </div>
    </div>
</template>
