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
        Swal.fire({ title: 'Batas Maksimal!', text: 'Maksimal 4 produk yang diperbolehkan. Hapus produk lama jika ingin menambah baru.', icon: 'warning' });
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
                Swal.fire({ title: 'Berhasil!', text: 'Produk berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke' });
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
                    Swal.fire({ title: 'Gagal!', text: page.props.errors.message, icon: 'error' });
                } else {
                    closeModal();
                    Swal.fire({ title: 'Berhasil!', text: 'Produk berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
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
            Swal.fire({ title: 'Berhasil!', text: 'Produk berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};
</script>

<template>
    <Head title="Products Management - PNetLab" />

    <AuthenticatedLayout>
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Products / Pricing</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Manage pricing packages displayed on the landing page (Max 4).</p>
            </div>
            <div class="flex gap-2">
                <button @click="openCreateModal" class="bg-[#065FD4] hover:bg-[#0056b3] text-white px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2" :class="{'opacity-50 cursor-not-allowed': products.length >= 4}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create New Product
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto">
            
            <!-- Table Section -->
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">All Products ({{ products.length }}/4)</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider w-16">No.</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Nama Paket</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Durasi (Hari)</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Rekomendasi?</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            <tr v-if="products.length === 0">
                                <td colspan="6" class="px-6 py-8 text-center text-[#606060]">No products found. Create one to get started.</td>
                            </tr>
                            <tr v-for="(product, index) in products" :key="product.id" class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    {{ index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-[14px] font-[500] text-[#0F0F0F]">{{ product.name }}</p>
                                    <p class="text-[12px] text-[#606060] mt-1">{{ product.description }}</p>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F]">
                                    {{ product.duration_days }}
                                </td>
                                <td class="px-6 py-4 text-[14px] font-bold text-[#065FD4]">
                                    {{ product.price }}
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="product.is_recommended" class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] bg-[#FFF4E5] text-[#FB8C00] border border-[#FB8C00]/20">
                                        ★ Recommended
                                    </span>
                                    <span v-else class="text-[#606060] text-[12px]">-</span>
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button @click="openEditModal(product)" class="p-1.5 text-[#065FD4] hover:bg-[#E5E5E5] rounded-[4px] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="openDeleteModal(product.id)" class="p-1.5 text-[#FF0000] hover:bg-[#FEF2F2] rounded-[4px] transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    {{ editMode ? 'Edit Produk' : 'Buat Produk Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Paket (cth: 1 Minggu)" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="duration_days" value="Durasi Akses (Hari)" />
                        <TextInput id="duration_days" type="number" min="1" class="mt-1 block w-full" v-model="form.duration_days" required />
                        <InputError class="mt-2" :message="form.errors.duration_days" />
                    </div>

                    <div>
                        <InputLabel for="price" value="Harga (cth: Rp 50.000)" />
                        <TextInput id="price" type="text" class="mt-1 block w-full" v-model="form.price" required />
                        <InputError class="mt-2" :message="form.errors.price" />
                    </div>

                    <div>
                        <InputLabel for="description" value="Deskripsi Singkat" />
                        <textarea id="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="2" v-model="form.description"></textarea>
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <div>
                        <InputLabel for="features" value="Fitur (Satu baris untuk setiap fitur)" />
                        <textarea id="features" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" v-model="form.features" placeholder="Akses Lab Selama 7 Hari&#10;Full Access PNETLab&#10;Support WhatsApp"></textarea>
                        <InputError class="mt-2" :message="form.errors.features" />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <input type="checkbox" v-model="form.is_recommended" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Jadikan Paket Rekomendasi (Highlight)</span>
                        </label>
                        <InputError class="mt-2" :message="form.errors.is_recommended" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Produk' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Hapus Produk
                </h2>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus produk ini? Aksi ini tidak dapat dibatalkan.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteProduct">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
