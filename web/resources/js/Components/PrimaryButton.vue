<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] px-5 py-2.5 text-sm font-semibold text-white shadow-md shadow-shop-primary/20 hover:shadow-lg transition-all focus:outline-hidden focus:ring-2 focus:ring-shop-primary/30 focus:ring-offset-1 disabled:opacity-50 disabled:pointer-events-none"
    >
        <slot />
    </button>
</template>
