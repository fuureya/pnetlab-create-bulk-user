<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-lg border border-gray-200 bg-white shadow-sm focus:border-blue-600 focus:ring focus:ring-blue-600/25 px-3 py-2 text-gray-900 font-['DM_Sans'] h-[40px] text-[14px]"
        v-model="model"
        ref="input"
    />
</template>
