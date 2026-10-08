<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 active:scale-[0.98] px-5 py-2.5 text-sm font-semibold text-gray-700 shadow-xs hover:border-gray-300 transition-all focus:outline-hidden focus:ring-2 focus:ring-gray-200 focus:ring-offset-1 disabled:opacity-50 disabled:pointer-events-none"
    >
        <slot />
    </button>
</template>
