<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
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
    products: {
        type: Array,
        required: true
    }
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const editMode = ref(false);
const currentProductId = ref(null);

const form = useForm({
    name: '',
    duration_days: '',
    price: '',
    description: '',
    features: '',
    is_recommended: false,
});

const openCreateModal = () => {
    if (props.products.length >= 4) {
        Swal.fire({
            title: 'Batas Maksimum Tercapai',
            text: 'Maksimal 4 paket produk yang dapat aktif secara bersamaan agar layout landing page tetap optimal.',
            icon: 'warning',
            confirmButtonText: 'Mengerti'
        });
        return;
    }
    editMode.value = false;
    form.reset();
    form.clearErrors();
    form.is_recommended = false;
    isModalOpen.value = true;
};

const openEditModal = (product) => {
    editMode.value = true;
    currentProductId.value = product.id;
    form.name = product.name;
    form.duration_days = product.duration_days;
    form.price = product.price;
    form.description = product.description || '';
    
    // Check if features is an array, join by newline
    if (Array.isArray(product.features)) {
        form.features = product.features.join('\n');
    } else {
        form.features = product.features || '';
    }

    form.is_recommended = Boolean(product.is_recommended);
    form.clearErrors();
    isModalOpen.value = true;
};

const openDeleteModal = (id) => {
    currentProductId.value = id;
    isDeleteModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    currentProductId.value = null;
};

const submit = () => {
    Swal.fire({ 
        title: 'Menyimpan...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });

    if (editMode.value) {
        form.put(route('products.update', currentProductId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Paket produk berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    } else {
        form.post(route('products.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Paket produk baru berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deleteProduct = () => {
    Swal.fire({ 
        title: 'Menghapus...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });
    router.delete(route('products.destroy', currentProductId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Paket produk berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};
</script>

<template>
    <Head title="Manajemen Paket Produk - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-box-archive"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Manajemen Paket Produk
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Atur harga, durasi akses, dan benefit paket langganan lab yang tampil di Landing Page.
                    </p>
                </div>
            </div>
            
            <div>
                <button 
                    @click="openCreateModal" 
                    class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg flex items-center gap-2"
                    :class="{'opacity-50 cursor-not-allowed': products.length >= 4}"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Paket Baru</span>
                </button>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
            <div class="px-6 py-4 border-b border-gray-200/80 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <span>Daftar Paket Produk Aktif</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-shop-primary/10 text-shop-primary">
                        {{ products.length }}/4 Paket
                    </span>
                </h3>
                <span class="text-xs text-gray-400">Maksimum 4 paket untuk menjaga grid landing page seimbang</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5 w-16 text-center">No</th>
                            <th class="px-6 py-3.5">Nama Paket</th>
                            <th class="px-6 py-3.5">Durasi</th>
                            <th class="px-6 py-3.5">Harga</th>
                            <th class="px-6 py-3.5">Highlight</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="products.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-box-open"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Belum ada paket produk</p>
                                    <p class="text-xs text-gray-400 mt-1">Buat paket pertama Anda agar pengguna dapat membeli voucher.</p>
                                </div>
                            </td>
                        </tr>
                        <tr 
                            v-for="(product, index) in products" 
                            :key="product.id" 
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono text-gray-400 text-center text-xs">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-bold text-gray-900">{{ product.name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ product.description || 'Tanpa deskripsi tambahan' }}</p>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-700">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 font-bold text-xs border border-gray-200">
                                    {{ product.duration_days }} Hari
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-mono font-extrabold text-shop-primary">
                                {{ product.price }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    v-if="product.is_recommended" 
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"
                                >
                                    <i class="fa-solid fa-crown text-[10px] text-amber-500"></i>
                                    <span>Rekomendasi</span>
                                </span>
                                <span v-else class="text-gray-400 text-xs font-mono">-</span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button 
                                        @click="openEditModal(product)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors" 
                                        title="Edit Paket"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(product.id)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors" 
                                        title="Hapus Paket"
                                    >
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid" :class="editMode ? 'fa-pen-to-square' : 'fa-box-archive'"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                {{ editMode ? 'Edit Paket Produk' : 'Tambah Paket Baru' }}
                            </h2>
                            <p class="text-xs text-gray-500">
                                {{ editMode ? 'Perbarui informasi harga dan daftar fitur paket lab' : 'Buat paket langganan lab baru untuk katalog landing page' }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Paket" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="name" 
                            type="text" 
                            class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                            placeholder="Contoh: Paket 1 Minggu Praktikum"
                            v-model="form.name" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.name" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <InputLabel for="duration_days" value="Durasi Akses (Hari)" class="font-semibold text-xs mb-1" />
                            <TextInput 
                                id="duration_days" 
                                type="number" 
                                min="1" 
                                class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                                placeholder="7"
                                v-model="form.duration_days" 
                                required 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.duration_days" />
                        </div>

                        <div>
                            <InputLabel for="price" value="Harga (cth: Rp 50.000)" class="font-semibold text-xs mb-1" />
                            <TextInput 
                                id="price" 
                                type="text" 
                                class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                                placeholder="Rp 50.000"
                                v-model="form.price" 
                                required 
                            />
                            <InputError class="mt-1 text-xs" :message="form.errors.price" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi Singkat" class="font-semibold text-xs mb-1" />
                        <textarea 
                            id="description" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
                            rows="2" 
                            v-model="form.description"
                            placeholder="Cocok untuk latihan praktikum dasar mandiri..."
                        ></textarea>
                        <InputError class="mt-1 text-xs" :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="features" value="Daftar Fitur (Satu baris per benefit)" class="font-semibold text-xs mb-1" />
                        <textarea 
                            id="features" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
                            rows="4" 
                            v-model="form.features" 
                            placeholder="Akses Lab PNETLab 24 Jam&#10;Full Root Access Pod&#10;Support WhatsApp Assistant&#10;Backup Konfigurasi Otomatis"
                        ></textarea>
                        <InputError class="mt-1 text-xs" :message="form.errors.features" />
                    </div>

                    <div class="pt-1 bg-amber-50/60 p-3.5 rounded-xl border border-amber-200/60">
                        <label class="flex items-center cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.is_recommended" 
                                class="rounded-md border-gray-300 text-shop-primary shadow-xs focus:ring-shop-primary w-4 h-4"
                            />
                            <span class="ml-2.5 text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                <i class="fa-solid fa-crown text-amber-500 text-xs"></i>
                                Tandai Sebagai Paket Rekomendasi (Highlight Populer di Landing Page)
                            </span>
                        </label>
                        <InputError class="mt-1 text-xs" :message="form.errors.is_recommended" />
                    </div>

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <SecondaryButton @click="closeModal">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Batal</span>
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>{{ editMode ? 'Simpan Perubahan' : 'Buat Paket' }}</span>
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
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Hapus Paket Produk?</h2>
                        <p class="text-xs text-gray-500">Paket ini tidak akan muncul lagi di halaman publik.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    Penghapusan produk tidak akan memengaruhi voucher yang sudah dibeli atau sedang aktif sebelumnya.
                </p>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeDeleteModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Batal</span>
                    </SecondaryButton>
                    <DangerButton @click="deleteProduct">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus Produk</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
