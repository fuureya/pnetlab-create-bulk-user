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
    let list = props.testimonials.data || [];
    if (!searchQuery.value) return list;
    const q = searchQuery.value.toLowerCase();
    return list.filter(t => 
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
        
        <!-- Header Toolbar -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-gray-200/80 shadow-xs mb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-lg font-bold shrink-0">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-poppins font-extrabold text-gray-900 tracking-tight">
                        Testimoni Pelanggan
                    </h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola ulasan & umpan balik praktikan yang dipublikasikan pada landing page utama.
                    </p>
                </div>
            </div>
            
            <div>
                <button 
                    @click="openCreateModal" 
                    class="bg-gradient-to-r from-shop-primary to-shop-secondary hover:brightness-110 active:scale-[0.98] text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all shadow-md shadow-shop-primary/20 hover:shadow-lg flex items-center gap-2"
                >
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Testimoni</span>
                </button>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5 mb-6">
            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Testimoni</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none">
                        {{ testimonials.total || testimonials.data.length }}
                    </h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Ulasan terverifikasi</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-shop-primary/10 text-shop-primary flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-quote-left"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Rating Kepuasan</p>
                    <h3 class="text-2xl font-extrabold text-gray-900 leading-none flex items-baseline gap-1">
                        <span>5.0</span>
                        <span class="text-xs font-normal text-gray-400">/ 5.0</span>
                    </h3>
                    <div class="flex items-center gap-1 text-amber-400 text-xs mt-1.5">
                        <i class="fa-solid fa-star" v-for="i in 5" :key="i"></i>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>

            <div class="bg-white border border-gray-200/90 rounded-2xl p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Publikasi</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 leading-none">Aktif</h3>
                    <p class="text-[11px] text-gray-500 mt-1.5 font-medium">Tampil di slider testimonial</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                    <i class="fa-solid fa-globe"></i>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white border border-gray-200/80 rounded-2xl overflow-hidden shadow-xs">
            <!-- Search & Filter bar -->
            <div class="px-6 py-4 border-b border-gray-200/80 bg-white flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="relative w-full md:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        placeholder="Cari nama, instansi, atau ulasan..." 
                        class="w-full h-10 pl-9 pr-4 text-xs bg-gray-50/50 border border-gray-200 rounded-xl focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-shop-primary/20 focus:border-shop-primary transition-all"
                    />
                </div>
                <p class="text-xs text-gray-500">
                    Menampilkan <strong class="text-gray-900">{{ filteredTestimonials.length }}</strong> dari {{ testimonials.total }} ulasan
                </p>
            </div>

            <!-- Table Content -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs sm:text-sm">
                    <thead>
                        <tr class="bg-gray-50/70 border-b border-gray-200 text-gray-500 font-mono text-[11px] uppercase">
                            <th class="px-6 py-3.5 w-16 text-center">No</th>
                            <th class="px-6 py-3.5">Praktikan</th>
                            <th class="px-6 py-3.5">Rating & Ulasan</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr v-if="filteredTestimonials.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-2xl mb-3">
                                        <i class="fa-solid fa-comment-slash"></i>
                                    </div>
                                    <p class="font-medium text-gray-600">Tidak ada ulasan ditemukan</p>
                                    <p class="text-xs text-gray-400 mt-1">Ubah kata kunci pencarian atau tambah testimoni baru.</p>
                                </div>
                            </td>
                        </tr>
                        <tr v-for="(testi, index) in filteredTestimonials" :key="testi.id" class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-6 py-4 text-center font-mono text-gray-400 text-xs">
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
                                        <p class="font-bold text-gray-900 leading-tight">{{ testi.name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5 flex items-center gap-1.5">
                                            <i class="fa-solid fa-briefcase text-[10px] text-gray-400"></i>
                                            {{ testi.role || 'Praktikan Lab' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-lg">
                                <div class="space-y-1">
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
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors" 
                                        title="Edit Testimoni"
                                    >
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </button>
                                    <button 
                                        @click="openDeleteModal(testi.id)" 
                                        class="w-8 h-8 rounded-xl flex items-center justify-center text-rose-600 hover:bg-rose-50 hover:text-rose-700 transition-colors" 
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
                    <template v-for="(link, index) in testimonials.links" :key="index">
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
                            <i class="fa-solid" :class="editMode ? 'fa-pen-to-square' : 'fa-comments'"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                                {{ editMode ? 'Edit Testimoni' : 'Buat Testimoni Baru' }}
                            </h2>
                            <p class="text-xs text-gray-500">
                                {{ editMode ? 'Perbarui kutipan atau profil praktikan' : 'Tambahkan ulasan praktikan yang akan ditampilkan di halaman utama' }}
                            </p>
                        </div>
                    </div>
                    <button @click="closeModal" class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Lengkap Praktikan" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="name" 
                            type="text" 
                            class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                            placeholder="Contoh: Rian Pratama" 
                            v-model="form.name" 
                            required 
                            autofocus 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="role" value="Jabatan / Instansi / Kampus" class="font-semibold text-xs mb-1" />
                        <TextInput 
                            id="role" 
                            type="text" 
                            class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 text-sm" 
                            placeholder="Contoh: Network Engineer / IT Telkom University" 
                            v-model="form.role" 
                        />
                        <InputError class="mt-1 text-xs" :message="form.errors.role" />
                    </div>

                    <div>
                        <InputLabel for="content" value="Isi Ulasan Testimoni" class="font-semibold text-xs mb-1" />
                        <textarea 
                            id="content" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
                            rows="4" 
                            placeholder="Ceritakan pengalaman belajar atau kestabilan server PNETLab..." 
                            v-model="form.content" 
                            required
                        ></textarea>
                        <InputError class="mt-1 text-xs" :message="form.errors.content" />
                    </div>

                    <div>
                        <InputLabel for="color_theme" value="Warna Aksen Avatar Inisial" class="font-semibold text-xs mb-1" />
                        <select 
                            id="color_theme" 
                            class="block w-full border-gray-200 bg-gray-50/50 focus:bg-white focus:border-shop-primary focus:ring-shop-primary/20 rounded-xl text-sm" 
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

                    <!-- Modal Footer -->
                    <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <SecondaryButton @click="closeModal">
                            <i class="fa-solid fa-xmark text-xs"></i>
                            <span>Batal</span>
                        </SecondaryButton>
                        <PrimaryButton :disabled="form.processing">
                            <i class="fa-solid fa-check text-xs"></i>
                            <span>{{ editMode ? 'Simpan Perubahan' : 'Buat Testimoni' }}</span>
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6">
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Hapus Testimoni Ini?</h2>
                        <p class="text-xs text-gray-500">Testimoni akan dihapus permanen dari basis data.</p>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mb-6 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    Ulasan ini tidak akan tampil lagi di halaman utama landing page. Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                    <SecondaryButton @click="closeDeleteModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Batal</span>
                    </SecondaryButton>
                    <DangerButton @click="deleteTestimonial">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Hapus Testimoni</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
