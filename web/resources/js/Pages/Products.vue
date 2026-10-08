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
    duration_days: 7,
    price: '',
    description: '',
    features: '',
    is_recommended: false
});

const openCreateModal = () => {
    if (props.products.length >= 4) {
        Swal.fire({ 
            title: 'Batas Maksimal!', 
            text: 'Maksimal 4 produk yang diperbolehkan untuk menjaga tampilan landing page tetap ideal. Hapus atau edit paket yang ada.', 
            icon: 'warning',
            confirmButtonColor: '#BF070F'
        });
        return;
    }
    editMode.value = false;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (product) => {
    editMode.value = true;
    currentProductId.value = product.id;
    form.name = product.name;
    form.duration_days = product.duration_days;
    form.price = product.price;
    form.description = product.description || '';
    form.features = product.features ? product.features.join('\n') : '';
    form.is_recommended = product.is_recommended === 1 || product.is_recommended === true;
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
    Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    if (editMode.value) {
        form.put(route('products.update', currentProductId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Paket produk berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke', confirmButtonColor: '#BF070F' });
            },
            onError: () => {
                Swal.close();
            }
        });
    } else {
        form.post(route('products.store'), {
            preserveScroll: true,
            onSuccess: (page) => {
                if(page.props.errors && page.props.errors.message) {
                    Swal.fire({ title: 'Gagal!', text: page.props.errors.message, icon: 'error', confirmButtonColor: '#BF070F' });
                } else {
                    closeModal();
                    Swal.fire({ title: 'Berhasil!', text: 'Paket produk baru berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke', confirmButtonColor: '#BF070F' });
                }
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deleteProduct = () => {
    Swal.fire({ title: 'Menghapus...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.delete(route('products.destroy', currentProductId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Paket produk berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke', confirmButtonColor: '#BF070F' });
        },
    });
};
</script>

<template>
    <Head title="Manajemen Paket Produk - Meraki Labs" />

    <AuthenticatedLayout>
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div>
                <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                    Manajemen Paket Produk
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Atur harga dan fitur paket langganan lab yang tampil di Landing Page (Maksimal 4 paket).
                </p>
            </div>
            
            <div>
                <button 
                    @click="openCreateModal" 
                    class="bg-shop-primary hover:bg-shop-secondary text-white px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-shop-md hover:shadow-shop-hover flex items-center gap-2"
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
                <h3 class="text-sm font-bold text-gray-900">
                    Daftar Paket Produk Aktif ({{ products.length }}/4)
                </h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5 w-16">No.</th>
                            <th class="px-6 py-3.5">Nama Paket</th>
                            <th class="px-6 py-3.5">Durasi (Hari)</th>
                            <th class="px-6 py-3.5">Harga</th>
                            <th class="px-6 py-3.5">Status Rekomendasi</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="products.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fa-solid fa-box-archive text-3xl mb-2 text-gray-300"></i>
                                <p class="text-sm">Belum ada paket produk yang terdaftar.</p>
                            </td>
                        </tr>
                        <tr 
                            v-for="(product, index) in products" 
                            :key="product.id" 
                            class="hover:bg-gray-50/60 transition-colors"
                        >
                            <td class="px-6 py-3.5 font-mono text-gray-500">
                                {{ index + 1 }}
                            </td>
                            <td class="px-6 py-3.5">
                                <p class="font-bold text-gray-900">{{ product.name }}</p>
                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ product.description }}</p>
                            </td>
                            <td class="px-6 py-3.5 font-mono text-gray-700">
                                {{ product.duration_days }} Hari
                            </td>
                            <td class="px-6 py-3.5 font-mono font-extrabold text-shop-primary">
                                {{ product.price }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span 
                                    v-if="product.is_recommended" 
                                    class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200"
                                >
                                    <i class="fa-solid fa-star text-[10px]"></i>
                                    <span>Recommended</span>
                                </span>
                                <span v-else class="text-gray-400 text-xs font-mono">-</span>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button 
                                        @click="openEditModal(product)" 
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" 
                                        title="Edit Produk"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(product.id)" 
                                        class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" 
                                        title="Hapus Produk"
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
                <h2 class="text-lg font-bold text-gray-900 mb-5">
                    {{ editMode ? 'Edit Paket Produk' : 'Tambah Paket Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Paket (cth: Paket 1 Minggu)" class="font-semibold text-xs mb-1" />
                        <TextInput id="name" type="text" class="block w-full" v-model="form.name" required autofocus />
                        <InputError class="mt-1" :message="form.errors.name" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <InputLabel for="duration_days" value="Durasi Akses (Hari)" class="font-semibold text-xs mb-1" />
                            <TextInput id="duration_days" type="number" min="1" class="block w-full" v-model="form.duration_days" required />
                            <InputError class="mt-1" :message="form.errors.duration_days" />
                        </div>

                        <div>
                            <InputLabel for="price" value="Harga (cth: Rp 50.000)" class="font-semibold text-xs mb-1" />
                            <TextInput id="price" type="text" class="block w-full" v-model="form.price" required />
                            <InputError class="mt-1" :message="form.errors.price" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi Singkat" class="font-semibold text-xs mb-1" />
                        <textarea 
                            id="description" 
                            class="block w-full border-gray-300 focus:border-shop-primary focus:ring-shop-primary rounded-xl text-sm" 
                            rows="2" 
                            v-model="form.description"
                            placeholder="Cocok untuk latihan praktikum dasar..."
                        ></textarea>
                        <InputError class="mt-1" :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="features" value="Daftar Fitur (Satu baris per fitur)" class="font-semibold text-xs mb-1" />
                        <textarea 
                            id="features" 
                            class="block w-full border-gray-300 focus:border-shop-primary focus:ring-shop-primary rounded-xl text-sm" 
                            rows="4" 
                            v-model="form.features" 
                            placeholder="Akses Lab Selama 7 Hari&#10;Full Access PNETLab&#10;Support WhatsApp 24/7"
                        ></textarea>
                        <InputError class="mt-1" :message="form.errors.features" />
                    </div>

                    <div class="pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input 
                                type="checkbox" 
                                v-model="form.is_recommended" 
                                class="rounded border-gray-300 text-shop-primary shadow-xs focus:ring-shop-primary w-4 h-4"
                            />
                            <span class="ml-2.5 text-xs font-semibold text-gray-700">Jadikan Paket Rekomendasi (Highlight Utama di Landing Page)</span>
                        </label>
                        <InputError class="mt-1" :message="form.errors.is_recommended" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Paket' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-2">Hapus Produk</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus paket produk ini? Paket tidak akan tampil lagi di halaman depan.
                </p>
                <div class="flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteProduct">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
