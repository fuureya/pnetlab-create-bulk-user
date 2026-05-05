<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    totalUsers: Number,
    totalAdmins: Number,
    recentUsers: Array,
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-indigo-500">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total PNetLab Users</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ totalUsers }}</div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800 border-l-4 border-emerald-500">
                        <div class="p-6">
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Admins</div>
                            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">{{ totalAdmins }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Recent Users</h3>
                            <Link :href="route('pnetlab-users.index')" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</Link>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700/50 text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase">
                                    <tr>
                                        <th class="px-6 py-3">Username</th>
                                        <th class="px-6 py-3">Name</th>
                                        <th class="px-6 py-3 text-center">POD</th>
                                        <th class="px-6 py-3 text-right">Created</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    <tr v-for="user in recentUsers" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100">{{ user.username }}</td>
                                        <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ user.name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">POD {{ user.pod }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-500">{{ new Date(user.created_at).toLocaleDateString() }}</td>
                                    </tr>
                                    <tr v-if="recentUsers.length === 0">
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">No users found.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
