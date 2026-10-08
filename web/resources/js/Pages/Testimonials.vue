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
    testimonials: {
        type: Object,
        required: true
    }
});

const searchQuery = ref('');
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

const filteredTestimonials = computed(() => {
    if (!searchQuery.value) return props.testimonials.data;
    const q = searchQuery.value.toLowerCase();
    return props.testimonials.data.filter(t => 
        (t.name && t.name.toLowerCase().includes(q)) ||
        (t.role && t.role.toLowerCase().includes(q)) ||
        (t.content && t.content.toLowerCase().includes(q))
    );
});

const openCreateModal = () => {
    editMode.value = false;
    form.reset();
    form.clearErrors();
    form.color_theme = 'primary';
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
    Swal.fire({ 
        title: 'Memproses...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });

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
                Swal.fire({ title: 'Berhasil!', text: 'Testimoni baru berhasil ditambahkan.', icon: 'success', confirmButtonText: 'Oke' });
            },
            onError: () => {
                Swal.close();
            }
        });
    }
};

const deleteTestimonial = () => {
    Swal.fire({ 
        title: 'Menghapus...', 
        text: 'Mohon tunggu sebentar', 
        allowOutsideClick: false, 
        didOpen: () => { Swal.showLoading(); } 
    });
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

const getThemeBadgeClasses = (theme) => {
    switch (theme) {
        case 'primary':
            return 'bg-shop-primary/10 text-shop-primary border-shop-primary/20';
        case 'secondary':
            return 'bg-purple-100 text-purple-700 border-purple-200';
        case 'success':
            return 'bg-emerald-100 text-emerald-700 border-emerald-200';
        case 'warning':
            return 'bg-amber-100 text-amber-700 border-amber-200';
        case 'info':
        default:
            return 'bg-sky-100 text-sky-700 border-sky-200';
    }
};
</script>

<template>
    <Head title="Manajemen Testimoni - Meraki Labs" />

    <AuthenticatedLayout>
        <!-- Header Actions -->
        <div class="px-4 md:px-8 py-6 border-b border-gray-100 bg-white">
            <div class="max-w-[2200px] mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-shop-primary/10 flex items-center justify-center text-shop-primary font-bold">
                            <i class="fa-solid fa-comments"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Testimoni Pelanggan</h1>
                            <p class="text-sm text-gray-500 mt-0.5">Kelola ulasan & umpan balik praktikan yang dipublikasikan pada landing page.</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button 
                        @click="openCreateModal" 
                        class="inline-flex items-center gap-2 bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-shop-primary/20 transition-all hover:shadow-lg"
                    >
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>Tambah Testimoni</span>
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
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Testimoni</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ testimonials.total || testimonials.data.length }}</p>
                        <p class="text-xs text-gray-400 mt-1">Ulasan terdaftar</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg">
                        <i class="fa-solid fa-quote-left"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Rating Kepuasan</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">5.0 <span class="text-xs font-normal text-gray-500">/ 5.0</span></p>
                        <div class="flex items-center gap-1 text-amber-400 text-xs mt-1">
                            <i class="fa-solid fa-star" v-for="i in 5" :key="i"></i>
                        </div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-star"></i>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-xs flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status Tampil</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">Publik</p>
                        <p class="text-xs text-gray-400 mt-1">Aktif di homepage carousel</p>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="fa-solid fa-globe"></i>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white border border-gray-100 shadow-xs rounded-2xl overflow-hidden">
                <!-- Search & Filter bar -->
                <div class="px-6 py-4 border-b border-gray-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="relative w-full md:w-80">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input 
                            v-model="searchQuery" 
                            type="text" 
                            placeholder="Cari nama, role, atau ulasan..." 
                            class="w-full pl-10 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-shop-primary/20 focus:border-shop-primary transition-all"
                        />
                    </div>
                    <p class="text-xs text-gray-500">
                        Menampilkan <strong class="text-gray-900">{{ filteredTestimonials.length }}</strong> dari {{ testimonials.total }} ulasan
                    </p>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/75 border-b border-gray-100">
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Praktikan</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider">Rating & Ulasan</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            <tr v-if="filteredTestimonials.length === 0">
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                            <i class="fa-regular fa-comment-dots"></i>
                                        </div>
                                        <p class="font-medium text-gray-600">Tidak ada testimoni yang ditemukan</p>
                                        <p class="text-xs text-gray-400 mt-1">Ubah kata kunci pencarian atau buat testimoni baru.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(testi, index) in filteredTestimonials" :key="testi.id" class="hover:bg-gray-50/60 transition-colors">
                                <td class="px-6 py-4 text-center text-xs font-medium text-gray-400">
                                    {{ (testimonials.current_page - 1) * testimonials.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div 
                                            class="w-10 h-10 rounded-xl border flex items-center justify-center font-bold text-sm shrink-0 shadow-xs"
                                            :class="getThemeBadgeClasses(testi.color_theme)"
                                        >
                                            {{ testi.name.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 leading-tight">{{ testi.name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                                <i class="fa-solid fa-briefcase text-[10px] text-gray-400"></i>
                                                {{ testi.role || 'Praktikan Lab' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-lg">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-1 text-amber-400 text-xs">
                                            <i class="fa-solid fa-star" v-for="i in 5" :key="i"></i>
                                            <span class="text-[11px] font-semibold text-gray-400 ml-1">5.0</span>
                                        </div>
                                        <p class="text-xs text-gray-700 italic leading-relaxed line-clamp-2">
                                            <i class="fa-solid fa-quote-left text-gray-300 mr-1 text-[10px]"></i>
                                            {{ testi.content }}
                                            <i class="fa-solid fa-quote-right text-gray-300 ml-1 text-[10px]"></i>
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button 
                                            @click="openEditModal(testi)" 
                                            class="p-2 text-gray-400 hover:text-shop-primary hover:bg-shop-primary/10 rounded-lg transition-colors" 
                                            title="Edit Testimoni"
                                        >
                                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                                        </button>
                                        <button 
                                            @click="openDeleteModal(testi.id)" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" 
                                            title="Hapus Testimoni"
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
                <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 bg-white" v-if="testimonials.links && testimonials.links.length > 3">
                    <span class="text-xs text-gray-500">
                        Menampilkan <strong class="text-gray-900">{{ testimonials.from || 0 }}</strong> - <strong class="text-gray-900">{{ testimonials.to || 0 }}</strong> dari {{ testimonials.total }} testimoni
                    </span>
                    <div class="flex items-center gap-1">
                        <button 
                            v-for="(link, index) in testimonials.links" 
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
                            <i class="fa-solid" :class="editMode ? 'fa-pen-to-square' : 'fa-plus'"></i>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">
                            {{ editMode ? 'Edit Testimoni' : 'Buat Testimoni Baru' }}
                        </h2>
                    </div>
                    <button @click="closeModal" class="text-gray-400 hover:text-gray-600 text-sm p-1 rounded-lg">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Pengguna / Praktikan" />
                        <TextInput 
                            id="name" 
                            type="text" 
                            class="mt-1 block w-full rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                            placeholder="Contoh: Rian Pratama" 
                            v-model="form.name" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Jabatan / Kampus / Instansi" />
                        <TextInput 
                            id="role" 
                            type="text" 
                            class="mt-1 block w-full rounded-xl border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20" 
                            placeholder="Contoh: Network Engineer / IT Telkom" 
                            v-model="form.role" 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.role" />
                    </div>

                    <div>
                        <InputLabel for="content" value="Isi Ulasan Testimoni" />
                        <textarea 
                            id="content" 
                            class="mt-1 block w-full border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl shadow-xs text-sm" 
                            rows="4" 
                            placeholder="Ceritakan pengalaman belajar atau menggunakan server PNetLab..." 
                            v-model="form.content" 
                            required
                        ></textarea>
                        <InputError class="mt-1 text-xs" :message="form.errors.content" />
                    </div>

                    <div>
                        <InputLabel for="color_theme" value="Warna Aksen Avatar" />
                        <select 
                            id="color_theme" 
                            class="mt-1 block w-full border-gray-200 focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl shadow-xs text-sm" 
                            v-model="form.color_theme" 
                            required
                        >
                            <option value="primary">Merah Crimson (Brand Utama)</option>
                            <option value="secondary">Ungu Lavender</option>
                            <option value="info">Biru Langit</option>
                            <option value="success">Hijau Emerald</option>
                            <option value="warning">Kuning Amber</option>
                        </select>
                        <InputError class="mt-1 text-xs" :message="form.errors.color_theme" />
                    </div>

                    <div class="pt-4 flex justify-end gap-3 border-t border-gray-100">
                        <SecondaryButton @click="closeModal" class="rounded-xl">Batal</SecondaryButton>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="bg-shop-primary hover:bg-shop-secondary text-white font-medium px-5 py-2 rounded-xl text-sm transition-all shadow-xs disabled:opacity-50"
                        >
                            {{ editMode ? 'Simpan Perubahan' : 'Buat Testimoni' }}
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
                    Hapus Testimoni Ini?
                </h2>
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus testimoni ini secara permanen? Data yang sudah dihapus tidak dapat dipulihkan.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal" class="rounded-xl">Batal</SecondaryButton>
                    <DangerButton @click="deleteTestimonial" class="rounded-xl">Hapus Sekarang</DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
