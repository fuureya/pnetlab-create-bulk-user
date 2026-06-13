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
    testimonials: {
        type: Object,
        required: true
    }
});

const isModalOpen = ref(false);
const isDeleteModalOpen = ref(false);
const editMode = ref(false);
const currentTestimonialId = ref(null);

const form = useForm({
    name: '',
    role: '',
    content: '',
    color_theme: 'primary',
});

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    isModalOpen.value = true;
};

const openEditModal = (testimonial) => {
    editMode.value = true;
    currentTestimonialId.value = testimonial.id;
    form.name = testimonial.name;
    form.role = testimonial.role || '';
    form.content = testimonial.content;
    form.color_theme = testimonial.color_theme || 'primary';
    form.clearErrors();
    isModalOpen.value = true;
};

const openDeleteModal = (id) => {
    currentTestimonialId.value = id;
    isDeleteModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    form.reset();
};

const closeDeleteModal = () => {
    isDeleteModalOpen.value = false;
    currentTestimonialId.value = null;
};

const submit = () => {
    Swal.fire({ title: 'Memproses...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    if (editMode.value) {
        form.put(route('testimonials.update', currentTestimonialId.value), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Testimoni berhasil diperbarui.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    } else {
        form.post(route('testimonials.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeModal();
                Swal.fire({ title: 'Berhasil!', text: 'Testimoni berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deleteTestimonial = () => {
    Swal.fire({ title: 'Menghapus...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    router.delete(route('testimonials.destroy', currentTestimonialId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Testimoni berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};

const changePage = (url) => {
    if (url) {
        router.get(url, {}, { preserveScroll: true, preserveState: true });
    }
};
</script>

<template>
    <Head title="Testimonials Management - PNetLab" />

    <AuthenticatedLayout>
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Testimoni</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Kelola data testimoni yang ditampilkan di halaman landing.</p>
            </div>
            <div class="flex gap-2">
                <button @click="openCreateModal" class="bg-[#065FD4] hover:bg-[#0056b3] text-white px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Buat Testimoni
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto">
            
            <!-- Table Section -->
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">Daftar Testimoni</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider w-16">No.</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Pengguna</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Isi Testimoni</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            <tr v-if="testimonials.data.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-[#606060]">Tidak ada testimoni.</td>
                            </tr>
                            <tr v-for="(testi, index) in testimonials.data" :key="testi.id" class="hover:bg-[#F8F8F8] transition-colors">
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    {{ (testimonials.current_page - 1) * testimonials.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :class="'w-10 h-10 rounded-[9999px] flex items-center justify-center font-[500] text-[16px] ' + 
                                            (testi.color_theme === 'primary' ? 'bg-[#065FD4]/10 text-[#065FD4]' :
                                            testi.color_theme === 'secondary' ? 'bg-[#C026D3]/10 text-[#C026D3]' :
                                            testi.color_theme === 'success' ? 'bg-[#10B981]/10 text-[#10B981]' :
                                            testi.color_theme === 'warning' ? 'bg-[#F59E0B]/10 text-[#F59E0B]' :
                                            'bg-[#3B82F6]/10 text-[#3B82F6]')"
                                        >
                                            {{ testi.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="text-[14px] font-[500] text-[#0F0F0F]">{{ testi.name }}</p>
                                            <p class="text-[12px] text-[#606060] mt-0.5">{{ testi.role }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F] max-w-[400px] truncate" :title="testi.content">
                                    "{{ testi.content }}"
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button @click="openEditModal(testi)" class="p-1.5 text-[#065FD4] hover:bg-[#E5E5E5] rounded-[4px] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="openDeleteModal(testi.id)" class="p-1.5 text-[#FF0000] hover:bg-[#FEF2F2] rounded-[4px] transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-[#E5E5E5] flex flex-col md:flex-row justify-between items-center gap-4 bg-[#FFFFFF]" v-if="testimonials.links.length > 3">
                    <span class="text-[12px] text-[#606060]">
                        Menampilkan {{ testimonials.from }} ke {{ testimonials.to }} dari {{ testimonials.total }} testimoni
                    </span>
                    <div class="flex items-center gap-1">
                        <button 
                            v-for="(link, index) in testimonials.links" 
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
                    {{ editMode ? 'Edit Testimoni' : 'Buat Testimoni Baru' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Pengguna" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Jabatan / Pekerjaan" />
                        <TextInput id="role" type="text" class="mt-1 block w-full" v-model="form.role" />
                        <InputError class="mt-2" :message="form.errors.role" />
                    </div>

                    <div>
                        <InputLabel for="content" value="Isi Testimoni" />
                        <textarea id="content" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" v-model="form.content" required></textarea>
                        <InputError class="mt-2" :message="form.errors.content" />
                    </div>

                    <div>
                        <InputLabel for="color_theme" value="Tema Warna (Avatar Inisial)" />
                        <select id="color_theme" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.color_theme" required>
                            <option value="primary">Primary (Biru)</option>
                            <option value="secondary">Secondary (Ungu)</option>
                            <option value="info">Info (Biru Muda)</option>
                            <option value="success">Success (Hijau)</option>
                            <option value="warning">Warning (Oranye)</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.color_theme" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closeModal">Batal</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Testimoni' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Hapus Testimoni
                </h2>
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus testimoni ini? Aksi ini tidak dapat dibatalkan.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Batal</SecondaryButton>
                    <DangerButton @click="deleteTestimonial">Hapus</DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
