<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    pendaftars: {
        type: Object,
        required: true
    }
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const editMode = ref(false);
const currentId = ref(null);
const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    role: 'user',
    password: '',
    password_confirmation: '',
});

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    showPassword.value = false;
    isModalOpen.value = true;
};

const openEditModal = (user) => {
    editMode.value = true;
    currentId.value = user.id;
    form.name = user.name;
    form.email = user.email;
    form.role = user.role || 'user';
    form.password = '';
    form.password_confirmation = '';
    form.clearErrors();
    showPassword.value = false;
    isModalOpen.value = true;
};

const openDeleteModal = (id) => {
    currentId.value = id;
    isDeleteModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    currentId.value = null;
};

const submit = () => {
    Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    if (editMode.value) {
        form.put(route('pendaftar.update', currentId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Data pendaftar berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    } else {
        form.post(route('pendaftar.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Pendaftar baru berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deletePendaftar = () => {
    Swal.fire({ title: 'Menghapus...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.delete(route('pendaftar.destroy', currentId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Pendaftar berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};

const changePage = (url) => {
    if (url) {
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Pendaftar Management - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Pendaftar</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Kelola data pengguna web yang mendaftar melalui halaman registrasi.</p>
            </div>
            <div class="flex gap-2">
                <button @click="openCreateModal" class="bg-[#065FD4] hover:bg-[#0056b3] text-white px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Pendaftar
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto">
            
            <!-- Table Section -->
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Daftar Registrasi User</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider w-16">No.</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Nama & Email</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Waktu Daftar</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            <tr v-if="pendaftars.data.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-[#606060]">Tidak ada pendaftar.</td>
                            </tr>
                            <tr v-for="(user, index) in pendaftars.data" :key="user.id" class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    {{ (pendaftars.current_page - 1) * pendaftars.per_page + index + 1 }}
                                </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-[9999px] flex items-center justify-center font-[500] text-[16px] bg-[#065FD4]/10 text-[#065FD4]">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="text-[14px] font-[500] text-[#0F0F0F]">{{ user.name }} <span v-if="user.role === 'admin'" class="ml-2 text-[10px] bg-red-100 text-[#065FD4] px-2 py-0.5 rounded-[4px] border border-[#065FD4]/20">Admin</span></p>
                                <p class="text-[12px] text-[#606060] mt-0.5">{{ user.email }}</p>
                            </div>
                        </div>
                    </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F]">
                                    {{ formatDate(user.created_at) }}
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button @click="openEditModal(user)" class="p-1.5 text-[#065FD4] hover:bg-[#E5E5E5] rounded-[4px] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="openDeleteModal(user.id)" class="p-1.5 text-[#FF0000] hover:bg-[#FEF2F2] rounded-[4px] transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-[#E5E5E5] flex flex-col md:flex-row justify-between items-center gap-4 bg-[#FFFFFF]" v-if="pendaftars.links.length > 3">
                    <span class="text-[12px] text-[#606060]">
                        Menampilkan {{ pendaftars.from }} ke {{ pendaftars.to }} dari {{ pendaftars.total }} pendaftar
                    </span>
                    <div class="flex items-center gap-1">
                        <button 
                            v-for="(link, index) in pendaftars.links" 
                            :key="index"
                            @click="changePage(link.url)"
                            v-html="link.label.replace('Previous', '&laquo;').replace('Next', '&raquo;')"
                            :disabled="!link.url"
                            class="min-w-[32px] h-[32px] flex items-center justify-center px-2 rounded-[4px] text-[14px] transition-colors"
                            :class="[
                                link.active ? 'bg-[#F2F2F2] text-[#0F0F0F] font-[500]' : 'text-[#606060] hover:bg-[#F2F2F2]',
                                !link.url ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                            ]"
                        ></button>
                    </div>
                </div>

            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    {{ editMode ? 'Edit Pendaftar' : 'Tambah Pendaftar Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Lengkap" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Email" />
                        <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Role Pengguna" />
                        <select id="role" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.role" required>
                            <option value="user">User Biasa</option>
                            <option value="admin">Administrator</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div class="relative">
                        <InputLabel for="password" :value="editMode ? 'Password Baru (Opsional)' : 'Password'" />
                        <div class="relative mt-1">
                            <TextInput :id="'password'" :type="showPassword ? 'text' : 'password'" class="block w-full pr-10" v-model="form.password" :required="!editMode" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                <svg v-if="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="relative">
                        <InputLabel for="password_confirmation" value="Konfirmasi Password" />
                        <div class="relative mt-1">
                            <TextInput :id="'password_confirmation'" :type="showPassword ? 'text' : 'password'" class="block w-full" v-model="form.password_confirmation" :required="!editMode && form.password !== ''" />
                        </div>
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Pendaftar' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Hapus Pendaftar
                </h2>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus akun pendaftar ini? Aksi ini tidak dapat dibatalkan.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Batal</SecondaryButton>
                    <DangerButton @click="deletePendaftar">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
