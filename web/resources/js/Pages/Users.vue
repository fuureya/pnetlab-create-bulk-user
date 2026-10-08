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
const visiblePasswords = ref({});
const showFormPassword = ref(false);
const copiedId = ref(null);
const searchQuery = ref('');
const statusFilter = ref('all');

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
    if (!props.vouchers.data || props.vouchers.data.length === 0) return;
    const text = props.vouchers.data.map(v => `Username: ${v.username} | Password: ${v.password || '-'} | Pod: ${v.pod_id} | Durasi: ${v.duration_days} Hari`).join("\n");
    navigator.clipboard.writeText(text);
    Swal.fire({
        title: 'Berhasil Disalin!',
        text: `${props.vouchers.data.length} kredensial voucher di halaman ini berhasil disalin ke clipboard.`,
        icon: 'success',
        timer: 2000,
        showConfirmButton: false
    });
};

const exportToCSV = () => {
    if (!props.vouchers.data || props.vouchers.data.length === 0) return;
    const rows = [["Username", "Password", "Pod ID", "Status", "Duration (Days)", "Expired At"]];
    props.vouchers.data.forEach(v => {
        rows.push([
            v.username, 
            v.password || '', 
            v.pod_id, 
            v.status, 
            v.duration_days,
            v.expired_at || ''
        ]);
    });
    const csvContent = "data:text/csv;charset=utf-8," + rows.map(e => e.join(",")).join("\n");
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `meraki-vouchers-${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

const filteredVouchers = computed(() => {
    let list = props.vouchers.data || [];
    if (statusFilter.value !== 'all') {
        list = list.filter(v => v.status === statusFilter.value);
    }
    if (searchQuery.value) {
        const q = searchQuery.value.toLowerCase();
        list = list.filter(v => 
            (v.username && v.username.toLowerCase().includes(q)) ||
            (v.pod_id && v.pod_id.toString().includes(q))
        );
    }
    return list;
});

const form = useForm({
    username: '',
    password: '',
    pod_id: 1,
    status: 'belum aktif',
    duration_days: 7
});

const bulkForm = useForm({
    count: 10,
    duration_days: 7
});

const openBulkModal = () => {
    bulkForm.reset();
    bulkForm.clearErrors();
    isBulkModalOpen.value = true;
};

const closeBulkModal = () => {
    isBulkModalOpen.value = false;
    bulkForm.reset();
};

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (voucher) => {
    editMode.value = true;
    currentVoucherId.value = voucher.id;
    form.username = voucher.username;
    form.password = voucher.password;
    form.pod_id = voucher.pod_id;
    form.status = voucher.status;
    form.duration_days = voucher.duration_days;
    form.clearErrors();
    isModalOpen.value = true;
};

const openDeleteModal = (id) => {
    currentVoucherId.value = id;
    isDeleteModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    currentVoucherId.value = null;
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
        });
    } else {
        form.post(route('users.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Voucher berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
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
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div>
                <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                    Manajemen Voucher Lab
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Kelola akun kredensial akses PNETLab, alokasi pod, dan masa aktif voucher.
                </p>
            </div>
            
            <div class="flex flex-wrap gap-2.5">
                <button 
                    @click="copyAllCredentials" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center gap-2"
                    title="Salin semua kredensial di halaman ini"
                >
                    <i class="fa-solid fa-copy text-xs"></i>
                    <span>Salin Semua</span>
                </button>

                <button 
                    @click="exportToCSV" 
                    class="bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-colors flex items-center gap-2"
                    title="Export data ke file CSV"
                >
                    <i class="fa-solid fa-file-csv text-sm"></i>
                    <span>Export CSV</span>
                </button>

                <button 
                    @click="openBulkModal" 
                    class="bg-gray-900 hover:bg-gray-800 text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-colors flex items-center gap-2"
                >
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>Bulk Generate</span>
                </button>

                <button 
                    @click="openCreateModal" 
                    class="bg-shop-primary hover:bg-shop-secondary text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-shop-md hover:shadow-shop-hover flex items-center gap-2"
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
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'all' ? 'bg-shop-primary text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Semua
                </button>
                <button 
                    @click="statusFilter = 'aktif'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'aktif' ? 'bg-emerald-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Aktif
                </button>
                <button 
                    @click="statusFilter = 'belum aktif'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'belum aktif' ? 'bg-amber-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Belum Aktif
                </button>
                <button 
                    @click="statusFilter = 'expired'" 
                    class="px-3 py-1.5 rounded-xl text-xs font-semibold transition-colors"
                    :class="statusFilter === 'expired' ? 'bg-rose-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                >
                    Expired
                </button>
            </div>

            <!-- Search Field -->
            <div class="relative w-full md:w-72">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-xs text-gray-400"></i>
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Cari username atau Pod ID..." 
                    class="w-full h-9 pl-9 pr-3 rounded-xl border border-gray-200 text-xs focus:border-shop-primary focus:ring-1 focus:ring-shop-primary"
                />
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5 w-16">No.</th>
                            <th class="px-6 py-3.5">Username</th>
                            <th class="px-6 py-3.5">Password</th>
                            <th class="px-6 py-3.5">Pod ID</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5">Expired At</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredVouchers.length === 0">
                            <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-ticket text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm">Tidak ada data voucher yang ditemukan.</p>
                            </td>
                        </tr>
                        <tr 
                            v-for="(voucher, index) in filteredVouchers" 
                            :key="voucher.id" 
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono text-gray-500">
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
                                        class="text-gray-400 hover:text-gray-700 p-1 transition-colors"
                                        title="Tampilkan / Sembunyikan"
                                    >
                                        <i v-if="!visiblePasswords[voucher.id]" class="fa-solid fa-eye text-xs"></i>
                                        <i v-else class="fa-solid fa-eye-slash text-xs"></i>
                                    </button>

                                    <button 
                                        v-if="voucher.password"
                                        @click="copyCredential(`User: ${voucher.username} | Pass: ${voucher.password}`, voucher.id)"
                                        class="text-gray-400 hover:text-shop-primary p-1 transition-colors"
                                        title="Salin Kredensial"
                                    >
                                        <i v-if="copiedId === voucher.id" class="fa-solid fa-check text-emerald-600 text-xs"></i>
                                        <i v-else class="fa-solid fa-copy text-xs"></i>
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-700">
                                <span class="px-2.5 py-0.5 rounded-lg bg-gray-100 font-bold text-xs">Pod {{ voucher.pod_id }}</span>
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold capitalize"
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
                                {{ voucher.expired_at ? new Date(voucher.expired_at).toLocaleString() : `Belum Aktif (${voucher.duration_days} Hari)` }}
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        v-if="voucher.status !== 'aktif'" 
                                        @click="manualActivate(voucher.id)" 
                                        class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" 
                                        title="Aktivasi API Manual"
                                    >
                                        <i class="fa-solid fa-circle-play text-sm"></i>
                                    </button>
                                    <button 
                                        v-if="voucher.status === 'aktif'" 
                                        @click="manualBlock(voucher.id)" 
                                        class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" 
                                        title="Blokir / Stop Akses"
                                    >
                                        <i class="fa-solid fa-circle-pause text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openEditModal(voucher)" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                        title="Edit Data"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(voucher.id)" 
                                        class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" 
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
                <div>Menampilkan {{ vouchers.from || 0 }} sampai {{ vouchers.to || 0 }} dari total {{ vouchers.total }} voucher</div>
                <div class="flex items-center gap-1" v-if="vouchers.links.length > 3">
                    <template v-for="(link, p) in vouchers.links" :key="p">
                        <Link 
                            v-if="link.url" 
                            :href="link.url" 
                            class="px-3 py-1.5 border border-gray-200 rounded-lg font-medium transition-colors" 
                            :class="link.active ? 'bg-shop-primary text-white border-shop-primary font-bold' : 'hover:bg-gray-100 text-gray-700'" 
                            v-html="link.label"
                        ></Link>
                        <span v-else class="px-3 py-1.5 border border-gray-100 rounded-lg opacity-40 text-gray-400" v-html="link.label"></span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5">
                    {{ editMode ? 'Edit Voucher Lab' : 'Buat Voucher Lab Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="username" value="Username PNETLab" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="username" 
                            type="text" 
                            class="block w-full" 
                            v-model="form.username" 
                            :readonly="editMode" 
                            :class="{'bg-gray-100 text-gray-500 cursor-not-allowed': editMode}" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1" :message="form.errors.username" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password Lab" class="font-semibold text-xs mb-1" />
                        <div class="relative">
                            <TextInput 
                                id="password" 
                                :type="showFormPassword ? 'text' : 'password'" 
                                class="block w-full pr-10" 
                                v-model="form.password" 
                                required 
                            />
                            <button 
                                type="button" 
                                @click="showFormPassword = !showFormPassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-700 transition-colors"
                            >
                                <i v-if="!showFormPassword" class="fa-solid fa-eye text-xs"></i>
                                <i v-else class="fa-solid fa-eye-slash text-xs"></i>
                            </button>
                        </div>
                        <InputError class="mt-1" :message="form.errors.password" />
                    </div>

                    <div v-if="editMode">
                        <InputLabel for="status" value="Status Voucher" class="font-semibold text-xs mb-1" />
                        <select 
                            id="status" 
                            v-model="form.status" 
                            class="block w-full border-gray-300 focus:border-shop-primary focus:ring-shop-primary rounded-xl text-sm"
                        >
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="terbeli">Terbeli</option>
                            <option value="belum aktif">Belum Aktif</option>
                            <option value="expired">Expired</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.status" />
                    </div>

                    <div>
                        <InputLabel for="duration_days" value="Paket Durasi Aktif" class="font-semibold text-xs mb-1" />
                        <select 
                            id="duration_days" 
                            v-model="form.duration_days" 
                            class="block w-full border-gray-300 focus:border-shop-primary focus:ring-shop-primary rounded-xl text-sm" 
                            required
                        >
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-1" :message="form.errors.duration_days" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Voucher' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Hapus Voucher</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus voucher ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteVoucher">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

        <!-- Bulk Generate Modal -->
        <Modal :show="isBulkModalOpen" @close="closeBulkModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-5">Bulk Generate Voucher Lab</h2>

                <form @submit.prevent="submitBulk" class="space-y-4">
                    <div>
                        <InputLabel for="bulk_count" value="Jumlah Voucher (Max 100)" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="bulk_count" 
                            type="number" 
                            min="1" 
                            max="100" 
                            class="block w-full" 
                            v-model="bulkForm.count" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1" :message="bulkForm.errors.count" />
                    </div>

                    <div>
                        <InputLabel for="bulk_duration_days" value="Paket Durasi Aktif" class="font-semibold text-xs mb-1" />
                        <select 
                            id="bulk_duration_days" 
                            v-model="bulkForm.duration_days" 
                            class="block w-full border-gray-300 focus:border-shop-primary focus:ring-shop-primary rounded-xl text-sm" 
                            required
                        >
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-1" :message="bulkForm.errors.duration_days" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <SecondaryButton @click="closeBulkModal">Batal</SecondaryButton>
                        <PrimaryButton :disabled="bulkForm.processing">
                            Generate Voucher
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
