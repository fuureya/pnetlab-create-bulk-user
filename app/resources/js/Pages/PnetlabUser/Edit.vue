<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    pnetlabUser: Object,
});

const form = useForm({
    username: props.pnetlabUser.username,
    name: props.pnetlabUser.name,
    email: props.pnetlabUser.email || '',
    pod: props.pnetlabUser.pod,
    role: props.pnetlabUser.role,
    expired_time: props.pnetlabUser.expired_time ? new Date(props.pnetlabUser.expired_time).toISOString().slice(0, 16) : '',
});

const submit = () => {
    form.put(route('pnetlab-users.update', props.pnetlabUser.id));
};
</script>

<template>
    <Head title="Edit PNETLab User" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit PNETLab User: {{ pnetlabUser.username }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <form @submit.prevent="submit" class="max-w-xl">
                            <div>
                                <InputLabel for="username" value="Username" />
                                <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus />
                                <InputError class="mt-2" :message="form.errors.username" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="name" value="Full Name" />
                                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="mt-4">
                                <InputLabel for="email" value="Email (Optional)" />
                                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <div>
                                    <InputLabel for="pod" value="POD ID" />
                                    <TextInput id="pod" type="number" class="mt-1 block w-full" v-model="form.pod" required />
                                    <InputError class="mt-2" :message="form.errors.pod" />
                                </div>
                                <div>
                                    <InputLabel for="role" value="Role" />
                                    <select id="role" v-model="form.role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option :value="1">User</option>
                                        <option :value="0">Admin</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.role" />
                                </div>
                            </div>

                            <div class="mt-4">
                                <InputLabel for="expired_time" value="Expired Date (Leave empty for forever)" />
                                <TextInput id="expired_time" type="datetime-local" class="mt-1 block w-full" v-model="form.expired_time" />
                                <InputError class="mt-2" :message="form.errors.expired_time" />
                            </div>

                            <div class="mt-8 flex items-center gap-4">
                                <PrimaryButton :disabled="form.processing">Update User</PrimaryButton>
                                <Link :href="route('pnetlab-users.index')" class="text-sm text-gray-600 dark:text-gray-400 underline">Cancel</Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
