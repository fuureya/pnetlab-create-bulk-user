<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-[14px] font-[500] text-[#2BA640]">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="font-['Roboto']">
            <div>
                <InputLabel for="email" value="Email" class="text-[#0F0F0F] font-[500]" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full h-[40px]"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-6">
                <InputLabel for="password" value="Password" class="text-[#0F0F0F] font-[500]" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full h-[40px]"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-3 text-[14px] text-[#606060]"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="mt-8 flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-[14px] text-[#065FD4] font-[500] hover:underline focus:outline-none"
                >
                    Forgot password?
                </Link>

                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Next
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
