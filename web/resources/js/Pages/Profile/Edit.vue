<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = computed(() => usePage().props.auth.user);

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Pengaturan Profil - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Profile Header Hero -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4 sm:gap-5">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-shop-primary to-shop-secondary text-white flex items-center justify-center font-extrabold text-2xl sm:text-3xl shadow-md shadow-shop-primary/20 shrink-0 border-2 border-white">
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5 flex-wrap">
                            <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                                {{ user.name }}
                            </h1>
                            <span 
                                v-if="user.role === 'admin'"
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-50 text-shop-primary border border-shop-primary/20"
                            >
                                <i class="fa-solid fa-user-shield text-[10px]"></i>
                                Administrator
                            </span>
                            <span 
                                v-else
                                class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200"
                            >
                                <i class="fa-solid fa-graduation-cap text-[10px] text-gray-500"></i>
                                Member Praktikan
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <span><i class="fa-regular fa-envelope text-gray-400"></i> {{ user.email }}</span>
                            <span class="text-gray-300">•</span>
                            <span>Bergabung sejak {{ formatDate(user.created_at) }}</span>
                        </p>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-2">
                    <div class="px-3.5 py-2 rounded-xl bg-gray-50 border border-gray-100 text-xs font-mono text-gray-600 flex items-center gap-2">
                        <i class="fa-solid fa-id-badge text-gray-400"></i>
                        <span>User ID: #{{ user.id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Forms Grid -->
        <div class="space-y-6">
            
            <!-- Update Profile Information -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <!-- Update Password -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200/80 shadow-xs">
                <UpdatePasswordForm />
            </div>

            <!-- Delete Account -->
            <div class="bg-white p-6 sm:p-8 rounded-2xl border border-rose-100 bg-rose-50/10 shadow-xs">
                <DeleteUserForm />
            </div>

        </div>

    </AuthenticatedLayout>
</template>
