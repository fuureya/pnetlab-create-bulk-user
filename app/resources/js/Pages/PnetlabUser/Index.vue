<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    users: Array,
});

const form = useForm({});

const deleteUser = (id) => {
    if (confirm('Are you sure you want to delete this user?')) {
        form.delete(route('pnetlab-users.destroy', id));
    }
};
</script>

<template>
    <Head title="PNETLab User Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    PNETLab User Management
                </h2>
                <Link :href="route('pnetlab-users.create')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm shadow-indigo-200">
                    Add New User
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div v-if="$page.props.flash?.message" class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg shadow-sm">
                    {{ $page.props.flash.message }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Username</th>
                                    <th class="px-6 py-4">Name / Email</th>
                                    <th class="px-6 py-4 text-center">POD ID</th>
                                    <th class="px-6 py-4 text-center">Role</th>
                                    <th class="px-6 py-4">Expired Time</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition duration-150">
                                    <td class="px-6 py-4 font-semibold text-gray-900 dark:text-gray-100">{{ user.username }}</td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ user.name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ user.email || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 text-xs font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400 rounded-full">
                                            POD {{ user.pod }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-600 dark:text-gray-400">
                                        {{ user.role === 0 ? 'Admin' : 'User' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        {{ user.expired_time ? new Date(user.expired_time).toLocaleString() : 'Forever' }}
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <Link :href="route('pnetlab-users.edit', user.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium text-sm transition">
                                            Edit
                                        </Link>
                                        <button @click="deleteUser(user.id)" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium text-sm transition">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="users.length === 0">
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
