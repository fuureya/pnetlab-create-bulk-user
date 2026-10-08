<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
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

const searchQuery = ref('');
const roleFilter = ref('all');
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

const filteredPendaftars = computed(() => {
    let list = props.pendaftars.data || [];
    
    if (roleFilter.value !== 'all') {
        list = list.filter(u => u.role === roleFilter.value);
    }
    
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(u => 
            (u.name && u.name.toLowerCase().includes(q)) ||
            (u.email && u.email.toLowerCase().includes(q))
        );
    }
    
    return list;
});

const stats = computed(() => {
    const list = props.pendaftars.data || [];
    const admins = list.filter(u => u.role === 'admin').length;
    const users = list.filter(u => u.role !== 'admin').length;
    return {
        total: props.pendaftars.total || list.length,
        admins,
        users
    };
});

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    form.role = 'user';
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
    Swal.fire({ 
        title: 'Memproses...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });

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
                Swal.fire({ title: 'Berhasil!', text: 'Pendaftar baru berhasil didaftarkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deletePendaftar = () => {
    Swal.fire({ 
        title: 'Menghapus...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });
    router.delete(route('pendaftar.destroy', currentId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Akun pendaftar berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
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
    <Head title="Manajemen Pendaftar - Meraki Labs" />

    <AuthenticatedLayout>
        <!-- Header Actions -->
        <div class="px-4 md:px-8 py-6 border-b border-gray-100 bg-white">
            <div class="max-w-[2200px] mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-shop-primary/10 flex items-center justify-center text-shop-primary font-bold">
                            <i class="fa-solid fa-users-gear"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Pendaftar Akun</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Kelola seluruh user & administrator yang terdaftar di platform Meraki Labs.</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        @click="openCreateModal" 
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-shop-primary/20 transition-all hover:shadow-lg"
                    >
                        <i class="fa-solid fa-user-plus text-xs"></i>
                        <span>Tambah Pendaftar</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-8 py-8 max-w-[2200px] mx-auto space-y-6">

            <!-- KPI Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendaftar</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ stats.total }}</p>
                        <p class="text-xs text-gray-400 mt-1">Akun di sistem database</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Administrator</p>
                        <p class="text-2xl font-bold text-shop-primary mt-1">{{ stats.admins }}</p>
                        <p class="text-xs text-gray-400 mt-1">Hak akses superadmin</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-red-50 text-shop-primary flex items-center justify-center text-lg">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">User Praktikan</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ stats.users }}</p>
                        <p class="text-xs text-gray-400 mt-1">Pengguna lab biasa</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white border border-gray-100 shadow-xs rounded-2xl overflow-hidden">
                <!-- Search & Filters -->
                <div class="px-6 py-4 border-b border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto">
                        <button 
                            @click="roleFilter = 'all'" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all shrink-0"
                            :class="roleFilter === 'all' ? 'bg-shop-primary text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        >
                            Semua Role
                        </button>
                        <button 
                            @click="roleFilter = 'admin'" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all shrink-0"
                            :class="roleFilter === 'admin' ? 'bg-shop-primary text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        >
                            <i class="fa-solid fa-shield-halved mr-1 text-[10px]"></i> Administrator
                        </button>
                        <button 
                            @click="roleFilter = 'user'" 
                            class="px-3.5 py-1.5 rounded-lg text-xs font-medium transition-all shrink-0"
                            :class="roleFilter === 'user' ? 'bg-shop-primary text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                        >
                            <i class="fa-solid fa-user mr-1 text-[10px]"></i> User Biasa
                        </button>
                    </div>

                    <div class="relative w-full md:w-80">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Cari nama atau email..." 
                            class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-shop-primary/20 focus:border-shop-primary transition-all"
                        />
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Identitas Pengguna</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Role & Hak Akses</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Terdaftar Sejak</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-if="filteredPendaftars.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                            <i class="fa-solid fa-user-slash"></i>
                                        </div>
                                        <p class="font-medium text-gray-600">Tidak ada pendaftar yang cocok</p>
                                        <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter atau tambahkan pendaftar baru.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(user, index) in filteredPendaftars" :key="user.id" class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 text-center text-xs font-medium text-gray-400">
                                    {{ (pendaftars.current_page - 1) * pendaftars.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div 
                                            class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-sm shrink-0 border"
                                            :class="user.role === 'admin' ? 'bg-shop-primary/10 text-shop-primary border-shop-primary/20' : 'bg-gray-100 text-gray-600 border-gray-200'"
                                        >
                                            {{ user.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 leading-tight">{{ user.name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                                <i class="fa-regular fa-envelope text-[11px] text-gray-400"></i>
                                                {{ user.email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span 
                                        v-if="user.role === 'admin'" 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-shop-primary border border-shop-primary/20"
                                    >
                                        <i class="fa-solid fa-user-shield text-[10px]"></i>
                                        Administrator
                                    </span>
                                    <span 
                                        v-else 
                                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200"
                                    >
                                        <i class="fa-solid fa-user text-[10px] text-gray-500"></i>
                                        User Biasa
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs text-gray-600">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i class="fa-regular fa-calendar-days text-gray-400"></i>
                                        {{ formatDate(user.created_at) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button 
                                            @click="openEditModal(user)" 
                                            class="p-2 text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 rounded-lg transition-colors" 
                                            title="Edit Pendaftar"
                                        >
                                            <i class="fa-solid fa-user-pen text-sm"></i>
                                        </button>
                                        <button 
                                            @click="openDeleteModal(user.id)" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Hapus Akun"
                                        >
                                            <i class="fa-solid fa-trash-can text-sm"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white" v-if="pendaftars.links && pendaftars.links.length > 3">
                    <span class="text-xs text-gray-500">
                        Menampilkan <strong class="text-gray-900">{{ pendaftars.from || 0 }}</strong> - <strong class="text-gray-900">{{ pendaftars.to || 0 }}</strong> dari {{ pendaftars.total }} pendaftar
                    </span>
                    <div class="flex items-center gap-1">
                        <button 
                            v-for="(link, index) in pendaftars.links" 
                            :key="index"
                            @click="changePage(link.url)"
                            v-html="link.label.replace('Previous', '&laquo;').replace('Next', '&raquo;')"
                            :disabled="!link.url"
                            class="min-w-[34px] h-[34px] flex items-center justify-center px-2.5 rounded-lg text-xs font-medium transition-colors"
                            :class="[
                                link.active ? 'bg-shop-primary text-white shadow-xs' : 'text-gray-600 hover:bg-gray-100',
                                !link.url ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer'
                            ]"
                        ></button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-shop-primary/10 text-shop-primary flex items-center justify-center text-sm font-bold">
                            <i class="fa-solid" :class="editMode ? 'fa-user-pen' : 'fa-user-plus'"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ editMode ? 'Edit Akun Pendaftar' : 'Tambah Pendaftar Baru' }}
                        </h2>
                    </div>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600 text-sm p-1 rounded-lg">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Lengkap" />
                        <TextInput 
                            id="name" 
                            type="text" 
                            class="mt-1 block w-full rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                            placeholder="Contoh: Ahmad Fauzan" 
                            v-model="form.name" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="email" value="Alamat Email" />
                        <TextInput 
                            id="email" 
                            type="email" 
                            class="mt-1 block w-full rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                            placeholder="nama@domain.com" 
                            v-model="form.email" 
                            required 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.email" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Role Pengguna" />
                        <select 
                            id="role" 
                            class="mt-1 block w-full border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl shadow-xs text-sm" 
                            v-model="form.role" 
                            required
                        >
                            <option value="user">User Biasa (Akses Pengguna)</option>
                            <option value="admin">Administrator (Akses Penuh)</option>
                        </select>
                        <InputError class="mt-1 text-xs" :message="form.errors.role" />
                    </div>

                    <div>
                        <InputLabel for="password" :value="editMode ? 'Password Baru (Kosongkan jika tidak diubah)' : 'Password'" />
                        <div class="relative mt-1">
                            <TextInput 
                                id="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                class="block w-full pr-10 rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                                placeholder="••••••••" 
                                v-model="form.password" 
                                :required="!editMode" 
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                            >
                                <i class="fa-solid text-sm" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <InputError class="mt-1 text-xs" :message="form.errors.password" />
                    </div>

                    <div v-if="!editMode || form.password !== ''">
                        <InputLabel for="password_confirmation" value="Konfirmasi Password" />
                        <div class="relative mt-1">
                            <TextInput 
                                id="password_confirmation" 
                                :type="showPassword ? 'text' : 'password'" 
                                class="block w-full rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                                placeholder="••••••••" 
                                v-model="form.password_confirmation" 
                                :required="!editMode && form.password !== ''" 
                            />
                        </div>
                        <InputError class="mt-1 text-xs" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                        <SecondaryButton @click="closeModal" class="rounded-xl">Batal</SecondaryButton>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-shop-primary hover:bg-shop-secondary text-white font-medium px-5 py-2 rounded-xl text-sm transition-all shadow-xs disabled:opacity-50"
                        >
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Pendaftar' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6">
                <div class="w-12 h-12 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-xl mb-4">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-900 mb-1">
                    Hapus Akun Pendaftar?
                </h2>
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus akun ini? Pengguna tidak akan dapat login kembali setelah dihapus.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal" class="rounded-xl">Batal</SecondaryButton>
                    <DangerButton @click="deletePendaftar" class="rounded-xl">Hapus Akun</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
