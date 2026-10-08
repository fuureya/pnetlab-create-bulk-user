<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';

const props = defineProps({
    vouchers: {
        type: Object,
        required: true
    }
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const isBulkModalOpen = ref(false);
const editMode = ref(false);
const currentVoucherId = ref(null);
const showFormPassword = ref(false);
const visiblePasswords = ref({});
const copiedId = ref(null);

const searchQuery = ref('');
const statusFilter = ref('all');

const form = useForm({
    username: '',
    password: '',
    duration_days: '7',
    status: 'aktif',
});

const bulkForm = useForm({
    count: 10,
    duration_days: '7',
});

const filteredVouchers = computed(() => {
    let list = props.vouchers.data || [];
    
    if (statusFilter.value !== 'all') {
        list = list.filter(v => v.status === statusFilter.value);
    }
    
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(v => 
            (v.username && v.username.toLowerCase().includes(q)) ||
            (v.pod_id && v.pod_id.toString().includes(q)) ||
            (v.status && v.status.toLowerCase().includes(q))
        );
    }
    
    return list;
});

const togglePassword = (id) => {
    visiblePasswords.value[id] = !visiblePasswords.value[id];
};

const copyCredential = (text, id) => {
    navigator.clipboard.writeText(text);
    copiedId.value = id;
    setTimeout(() => {
        copiedId.value = null;
    }, 2000);
};

const copyAllCredentials = () => {
    if (filteredVouchers.value.length === 0) return;
    const all = filteredVouchers.value.map(v => `User: ${v.username} | Pass: ${v.password} | Pod: ${v.pod_id}`).join('\n');
    navigator.clipboard.writeText(all);
    Swal.fire({
        title: 'Berhasil Disalin!',
        text: `${filteredVouchers.value.length} kredensial telah disalin ke clipboard.`,
        icon: 'success',
        timer: 1800,
        showConfirmButton: false
    });
};

const exportToCSV = () => {
    if (filteredVouchers.value.length === 0) {
        Swal.fire({ title: 'Data Kosong', text: 'Tidak ada data voucher untuk diekspor.', icon: 'info' });
        return;
    }
    let csv = 'No,Username,Password,Pod ID,Status,Durasi Hari,Expired At\n';
    filteredVouchers.value.forEach((v, idx) => {
        csv += `"${idx + 1}","${v.username}","${v.password}","Pod ${v.pod_id}","${v.status}","${v.duration_days}","${v.expired_at || '-'}"\n`;
    });
    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `vouchers-meraki-${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    form.status = 'aktif';
    form.duration_days = '7';
    showFormPassword.value = false;
    isModalOpen.value = true;
};

const openEditModal = (voucher) => {
    editMode.value = true;
    currentVoucherId.value = voucher.id;
    form.username = voucher.username;
    form.password = voucher.password;
    form.duration_days = voucher.duration_days;
    form.status = voucher.status;
    form.clearErrors();
    showFormPassword.value = false;
    isModalOpen.value = true;
};

const openDeleteModal = (id) => {
    currentVoucherId.value = id;
    isDeleteModalOpen.value = true;
};

const openBulkModal = () => {
    bulkForm.reset();
    bulkForm.clearErrors();
    bulkForm.count = 10;
    bulkForm.duration_days = '7';
    isBulkModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    currentVoucherId.value = null;
};

const closeBulkModal = () => {
    isBulkModalOpen.value = false;
    bulkForm.reset();
};

const submit = () => {
    Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

    if (editMode.value) {
        form.put(route('users.update', currentVoucherId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Voucher berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    } else {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Voucher baru berhasil dibuat.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deleteVoucher = () => {
    Swal.fire({ title: 'Menghapus...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.delete(route('users.destroy', currentVoucherId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Voucher berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};

const submitBulk = () => {
    Swal.fire({ title: 'Generate Massal...', text: 'Membuat voucher, mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    bulkForm.post(route('users.bulk_store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeBulkModal();
            Swal.fire({ title: 'Berhasil!', text: `${bulkForm.count} Voucher berhasil di-generate.`, icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};

const manualActivate = (id) => {
    Swal.fire({ title: 'Aktivasi...', text: 'Mengirim request API Meraki Labs', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.post(route('users.manual_activate', id), {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.errors && page.props.errors.api) {
                Swal.fire({ title: 'Gagal!', text: page.props.errors.api, icon: 'error', confirmButtonText: 'Tutup' });
            } else {
                Swal.fire({ title: 'Berhasil!', text: 'User berhasil diaktivasi.', icon: 'success', confirmButtonText: 'Oke' });
            }
        },
        onError: (errors) => {
            Swal.fire({ title: 'Error!', text: errors.api || 'Terjadi kesalahan sistem', icon: 'error', confirmButtonText: 'Tutup' });
        }
    });
};

const manualBlock = (id) => {
    Swal.fire({ title: 'Block...', text: 'Mengirim request API Meraki Labs', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.post(route('users.manual_block', id), {}, {
        preserveScroll: true,
        onSuccess: (page) => {
            if (page.props.errors && page.props.errors.api) {
                Swal.fire({ title: 'Gagal!', text: page.props.errors.api, icon: 'error', confirmButtonText: 'Tutup' });
            } else {
                Swal.fire({ title: 'Berhasil!', text: 'User berhasil diblokir.', icon: 'success', confirmButtonText: 'Oke' });
            }
        },
        onError: (errors) => {
            Swal.fire({ title: 'Error!', text: errors.api || 'Terjadi kesalahan sistem', icon: 'error', confirmButtonText: 'Tutup' });
        }
    });
};
</script>

<template>
    <Head title="Manajemen Voucher Lab - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Manajemen Voucher Lab
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola akun kredensial akses PNETLab, alokasi pod, dan masa aktif voucher.
                    </p>
                </div>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <button 
                    @click="copyAllCredentials" 
                    class="bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-700 border border-gray-200 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs flex items-center gap-2"
                    title="Salin semua kredensial di tabel saat ini"
                >
                    <i class="fa-solid fa-copy text-xs text-gray-500"></i>
                    <span>Salin Semua</span>
                </button>

                <button 
                    @click="exportToCSV" 
                    class="bg-emerald-50 hover:bg-emerald-100 active:scale-[0.98] text-emerald-700 border border-emerald-200 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all shadow-xs flex items-center gap-2"
                    title="Export data ke file CSV"
                >
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </button>

                <button 
                    @click="openBulkModal" 
                    class="bg-gray-900 hover:bg-gray-800 active:scale-[0.98] text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md flex items-center gap-2"
                >
                    <i class="fa-solid fa-layer-group text-xs text-purple-300"></i>
                    <span>Bulk Generate</span>
                </button>

                <button 
                    @click="openCreateModal" 
                    class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg flex items-center gap-2"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Buat Voucher</span>
                </button>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white border border-gray-200/80 rounded-2xl p-4 shadow-xs mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <!-- Tabs Status -->
            <div class="flex flex-wrap gap-1.5 w-full md:w-auto">
                <button 
                    @click="statusFilter = 'all'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all"
                    :class="statusFilter === 'all' ? 'bg-shop-primary text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Semua
                </button>
                <button 
                    @click="statusFilter = 'aktif'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'aktif' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                    Aktif
                </button>
                <button 
                    @click="statusFilter = 'belum aktif'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'belum aktif' ? 'bg-amber-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-clock text-[10px]"></i>
                    Belum Aktif
                </button>
                <button 
                    @click="statusFilter = 'expired'" 
                    class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all flex items-center gap-1.5"
                    :class="statusFilter === 'expired' ? 'bg-rose-600 text-white shadow-xs' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    <i class="fa-solid fa-ban text-[10px]"></i>
                    Expired
                </button>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400"></i>
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Cari username atau Pod ID..." 
                    class="w-full h-10 pl-9 pr-4 rounded-xl border border-gray-200 bg-gray-50/50 text-xs focus:bg-white focus:border-shop-primary focus:ring-2 focus:ring-shop-primary/20 transition-all"
                />
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5 w-16 text-center">No</th>
                            <th class="px-6 py-3.5">Username</th>
                            <th class="px-6 py-3.5">Password</th>
                            <th class="px-6 py-3.5">Pod ID</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Masa Berlaku</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredVouchers.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-ticket"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Tidak ada voucher yang cocok</p>
                                    <p class="text-xs text-gray-400 mt-1">Ubah kata kunci pencarian atau buat voucher baru.</p>
                                </div>
                            </td>
                        </tr>
                        <tr 
                            v-for="(voucher, index) in filteredVouchers" 
                            :key="voucher.id" 
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono text-gray-400 text-center text-xs">
                                {{ (vouchers.current_page - 1) * vouchers.per_page + index + 1 }}
                            </td>
                            <td class="px-6 py-3.5 font-mono font-bold text-gray-900">
                                {{ voucher.username }}
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-700">
                                <div class="flex items-center gap-2">
                                    <span v-if="!visiblePasswords[voucher.id]">••••••••</span>
                                    <span v-else class="text-gray-900 font-semibold">{{ voucher.password }}</span>
                                    
                                    <button 
                                        @click="togglePassword(voucher.id)" 
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors"
                                        title="Tampilkan / Sembunyikan"
                                    >
                                        <i v-if="!visiblePasswords[voucher.id]" class="fa-solid fa-eye text-xs"></i>
                                        <i v-else class="fa-solid fa-eye-slash text-xs"></i>
                                    </button>

                                    <button 
                                        v-if="voucher.password"
                                        @click="copyCredential(`User: ${voucher.username} | Pass: ${voucher.password}`, voucher.id)"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 transition-colors"
                                        title="Salin Kredensial"
                                    >
                                        <i v-if="copiedId === voucher.id" class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                        <i v-else class="fa-solid fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-700">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 font-bold text-xs border border-gray-200">Pod {{ voucher.pod_id }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold capitalize"
                                    :class="{
                                        'bg-emerald-50 text-emerald-700 border border-emerald-200': voucher.status === 'aktif',
                                        'bg-rose-50 text-rose-700 border border-rose-200': voucher.status === 'nonaktif' || voucher.status === 'expired',
                                        'bg-amber-50 text-amber-700 border border-amber-200': voucher.status === 'belum aktif',
                                        'bg-blue-50 text-blue-700 border border-blue-200': voucher.status === 'terbeli'
                                    }"
                                >
                                    <i class="fa-solid fa-circle text-[6px]"></i>
                                    <span>{{ voucher.status }}</span>
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-xs text-gray-500 font-mono">
                                {{ voucher.expired_at ? new Date(voucher.expired_at).toLocaleString('id-ID') : `Belum Aktif (${voucher.duration_days} Hari)` }}
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button 
                                        v-if="voucher.status !== 'aktif'" 
                                        @click="manualActivate(voucher.id)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700 transition-colors" 
                                        title="Aktivasi API Manual"
                                    >
                                        <i class="fa-solid fa-circle-play text-sm"></i>
                                    </button>
                                    <button 
                                        v-if="voucher.status === 'aktif'" 
                                        @click="manualBlock(voucher.id)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-amber-600 hover:bg-amber-50 hover:text-amber-700 transition-colors" 
                                        title="Blokir / Hentikan Akses"
                                    >
                                        <i class="fa-solid fa-circle-pause text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openEditModal(voucher)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors" 
                                        title="Edit Data Voucher"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(voucher.id)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors" 
                                        title="Hapus Voucher"
                                    >
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 gap-3">
                <div>Menampilkan <strong class="text-gray-900">{{ vouchers.from || 0 }}</strong> sampai <strong class="text-gray-900">{{ vouchers.to || 0 }}</strong> dari total {{ vouchers.total }} voucher</div>
                <div class="flex items-center gap-1" v-if="vouchers.links && vouchers.links.length > 3">
                    <template v-for="(link, index) in vouchers.links" :key="index">
                        <Link 
                            v-if="link.url"
                            :href="link.url"
                            v-html="link.label.replace('Previous', '&laquo;').replace('Next', '&raquo;')"
                            class="min-w-[34px] h-[34px] flex items-center justify-center px-2.5 rounded-xl text-xs font-medium transition-all"
                            :class="[
                                link.active ? 'bg-shop-primary text-white shadow-xs font-bold' : 'text-gray-600 hover:bg-gray-100',
                            ]"
                        />
                        <span 
                            v-else
                            v-html="link.label.replace('Previous', '&laquo;').replace('Next', '&raquo;')"
                            class="min-w-[34px] h-[34px] flex items-center justify-center px-2.5 rounded-xl text-xs text-gray-300 opacity-50 cursor-not-allowed"
                        />
                    </template>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid" :class="editMode ? 'fa-pen-to-square' : 'fa-ticket'"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                {{ editMode ? 'Edit Voucher Lab' : 'Buat Voucher Lab Baru' }}
                            </h2>
                            <p class="text-xs text-gray-500">
                                {{ editMode ? 'Perbarui kredensial atau status masa aktif voucher' : 'Kredensial login akun PNETLab dan durasi aktif' }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="username" value="Username PNETLab" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="username" 
                            type="text" 
                            class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                            placeholder="Contoh: labuser01"
                            v-model="form.username" 
                            :readonly="editMode" 
                            :class="{'bg-gray-100 text-gray-500 cursor-not-allowed': editMode}" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.username" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password Lab" class="font-semibold text-xs mb-1" />
                        <div class="relative">
                            <TextInput 
                                id="password" 
                                :type="showFormPassword ? 'text' : 'password'" 
                                class="block w-full pr-10 rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                                placeholder="••••••••"
                                v-model="form.password" 
                                required 
                            />
                            <button 
                                type="button" 
                                @click="showFormPassword = !showFormPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 transition-colors"
                            >
                                <i class="fa-solid text-xs" :class="showFormPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                            </button>
                        </div>
                        <InputError class="mt-1 text-xs" :message="form.errors.password" />
                    </div>

                    <div v-if="editMode">
                        <InputLabel for="status" value="Status Voucher" class="font-semibold text-xs mb-1" />
                        <select 
                            id="status" 
                            v-model="form.status" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm"
                        >
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="terbeli">Terbeli</option>
                            <option value="belum aktif">Belum Aktif</option>
                            <option value="expired">Expired</option>
                        </select>
                        <InputError class="mt-1 text-xs" :message="form.errors.status" />
                    </div>

                    <div>
                        <InputLabel for="duration_days" value="Paket Durasi Aktif" class="font-semibold text-xs mb-1" />
                        <select 
                            id="duration_days" 
                            v-model="form.duration_days" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
                            required
                        >
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-1 text-xs" :message="form.errors.duration_days" />
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <SecondaryButton @click="closeModal">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Batal</span>
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>{{ editMode ? 'Simpan Perubahan' : 'Buat Voucher' }}</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Hapus Voucher Lab?</h2>
                        <p class="text-xs text-gray-500">Tindakan ini permanen dan tidak dapat dipulihkan.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    Voucher yang dihapus akan dicabut dari basis data dan pengguna tidak dapat menggunakan akun tersebut untuk masuk ke PNETLab.
                </p>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeDeleteModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Batal</span>
                    </SecondaryButton>
                    <DangerButton @click="deleteVoucher">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus Voucher</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <!-- Bulk Generate Modal -->
        <Modal :show="isBulkModalOpen" @close="closeBulkModal">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                Bulk Generate Voucher
                            </h2>
                            <p class="text-xs text-gray-500">
                                Buat puluhan akun voucher secara otomatis sekaligus
                            </p>
                        </div>
                    </div>
                    <button @click="closeBulkModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submitBulk" class="space-y-4">
                    <div>
                        <InputLabel for="bulk_count" value="Jumlah Voucher (Maksimal 100)" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="bulk_count" 
                            type="number" 
                            min="1" 
                            max="100" 
                            class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                            v-model="bulkForm.count" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="bulkForm.errors.count" />
                    </div>

                    <div>
                        <InputLabel for="bulk_duration_days" value="Paket Durasi Aktif" class="font-semibold text-xs mb-1" />
                        <select 
                            id="bulk_duration_days" 
                            v-model="bulkForm.duration_days" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
                            required
                        >
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-1 text-xs" :message="bulkForm.errors.duration_days" />
                    </div>

                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200/70 text-xs text-amber-800 flex items-start gap-2.5">
                        <i class="fa-solid fa-circle-info text-amber-600 mt-0.5 shrink-0"></i>
                        <span>Sistem akan mengalokasikan nomor Pod ID secara berurutan dan mengenerate password acak yang aman untuk setiap akun.</span>
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <SecondaryButton @click="closeBulkModal">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Batal</span>
                        </SecondaryButton>
                        <button 
                            type="submit"
                            :disabled="bulkForm.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-900 hover:bg-gray-800 active:scale-[0.98] px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all disabled:opacity-50"
                        >
                            <i class="fa-solid fa-bolt text-xs text-purple-300"></i>
                            <span>Generate Sekarang</span>
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
