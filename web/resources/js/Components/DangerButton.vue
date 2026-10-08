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
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 hover:bg-rose-700 active:scale-[0.98] px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-rose-600/20 hover:shadow-lg transition-all focus:outline-hidden focus:ring-2 focus:ring-rose-500/40 focus:ring-offset-1 disabled:opacity-50 disabled:pointer-events-none"
    >
        <slot />
    </button>
</template>
