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

const togglePassword = (id) => {
    visiblePasswords.value[id] = !visiblePasswords.value[id];
};

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
    router.delete(route('users.destroy', currentVoucherId.value), {
        preserveScroll: true,
        onSuccess: () => {
            closeDeleteModal();
            Swal.fire({ title: 'Berhasil!', text: 'Voucher berhasil dihapus.', icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};

const submitBulk = () => {
    bulkForm.post(route('users.bulk_store'), {
        preserveScroll: true,
        onSuccess: () => {
            closeBulkModal();
            Swal.fire({ title: 'Berhasil!', text: `${bulkForm.count} Voucher berhasil di-generate.`, icon: 'success', confirmButtonText: 'Oke' });
        },
    });
};
</script>

<template>
    <Head title="Vouchers Management - PNetLab" />

    <AuthenticatedLayout>
        
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-4 md:px-6 py-6 border-b border-[#E5E5E5] gap-4">
            <div>
                <h1 class="text-[24px] font-[700] text-[#0F0F0F]">Vouchers Management</h1>
                <p class="text-[14px] text-[#606060] font-[400] mt-1">Manage PNetLab vouchers, pods, and access expiration.</p>
            </div>
            <div class="flex gap-2">
                <button @click="openBulkModal" class="bg-[#E5E5E5] hover:bg-[#D4D4D4] text-[#0F0F0F] px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                    Bulk Generate
                </button>
                <button @click="openCreateModal" class="bg-[#065FD4] hover:bg-[#0056b3] text-white px-[16px] h-[36px] rounded-[9999px] text-[14px] font-[500] transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Create New Voucher
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-4 md:px-6 py-6 max-w-[2200px] mx-auto">
            
            <!-- Table Section -->
            <div class="bg-[#FFFFFF] border border-[#E5E5E5] rounded-[12px] overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E5E5E5] flex justify-between items-center bg-[#F8F8F8]">
                    <h3 class="text-[16px] font-[500] text-[#0F0F0F]">All Vouchers</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-[#FFFFFF] border-b border-[#E5E5E5]">
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider w-16">No.</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">User (Username)</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Password</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Pod ID</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider">Expired At</th>
                                <th class="px-6 py-3 text-[12px] font-[500] text-[#606060] uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#E5E5E5]">
                            <tr v-if="vouchers.data.length === 0">
                                <td colspan="7" class="px-6 py-8 text-center text-[#606060]">No vouchers found. Create one to get started.</td>
                            </tr>
                            <tr v-for="(voucher, index) in vouchers.data" :key="voucher.id" class="hover:bg-[#F8F8F8] transition-colors" :class="{'bg-[#FEF2F2]/30': voucher.status === 'nonaktif'}">
                                <td class="px-6 py-4 text-[14px] text-[#606060]">
                                    {{ (vouchers.current_page - 1) * vouchers.per_page + index + 1 }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-[#065FD4] text-white flex items-center justify-center text-[12px] font-[500]">
                                            {{ voucher.username.charAt(0).toUpperCase() }}
                                        </div>
                                        <div>
                                            <p class="text-[14px] font-[500] text-[#0F0F0F]">{{ voucher.username }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F]">
                                    <div class="flex items-center justify-between w-32">
                                        <span class="font-['Roboto_Mono'] text-[#606060] tracking-widest" v-if="!visiblePasswords[voucher.id]">••••••••</span>
                                        <span class="font-['Roboto_Mono']" v-else>{{ voucher.password }}</span>
                                        <button @click="togglePassword(voucher.id)" class="text-[#606060] hover:text-[#065FD4] transition-colors p-1 rounded-full hover:bg-[#F2F2F2]">
                                            <!-- Eye Open Icon -->
                                            <svg v-if="!visiblePasswords[voucher.id]" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <!-- Eye Closed Icon -->
                                            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-[14px] text-[#0F0F0F] font-['Roboto_Mono']">
                                    {{ voucher.pod_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-[2px] text-[12px] font-[500] capitalize"
                                        :class="{
                                            'bg-[#e6f4ea] text-[#2BA640] border border-[#2BA640]/20': voucher.status === 'aktif',
                                            'bg-[#FEF2F2] text-[#FF0000] border border-[#FF0000]/20': voucher.status === 'nonaktif',
                                            'bg-[#FFF4E5] text-[#FB8C00] border border-[#FB8C00]/20': voucher.status === 'belum aktif',
                                            'bg-[#E8F0FE] text-[#065FD4] border border-[#065FD4]/20': voucher.status === 'terbeli',
                                            'bg-[#F2F2F2] text-[#606060] border border-[#606060]/20': voucher.status === 'expired'
                                        }"
                                    >
                                        {{ voucher.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-[14px]" :class="voucher.expired_at ? 'text-[#0F0F0F]' : 'text-[#606060]'">
                                    {{ voucher.expired_at ? new Date(voucher.expired_at).toLocaleString() : `Belum Diaktifkan (${voucher.duration_days} Hari)` }}
                                </td>
                                <td class="px-6 py-4 text-right flex justify-end gap-2">
                                    <button @click="openEditModal(voucher)" class="p-1.5 text-[#065FD4] hover:bg-[#E5E5E5] rounded-[4px] transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    </button>
                                    <button @click="openDeleteModal(voucher.id)" class="p-1.5 text-[#FF0000] hover:bg-[#FEF2F2] rounded-[4px] transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Footer -->
                <div class="px-6 py-4 border-t border-[#E5E5E5] flex items-center justify-between text-[14px] text-[#606060]">
                    <div>Showing {{ vouchers.from || 0 }} to {{ vouchers.to || 0 }} of {{ vouchers.total }} vouchers</div>
                    <div class="flex items-center gap-2" v-if="vouchers.links.length > 3">
                        <template v-for="(link, p) in vouchers.links" :key="p">
                            <Link v-if="link.url" :href="link.url" class="px-3 py-1 border border-[#E5E5E5] rounded-[4px]" :class="link.active ? 'bg-[#065FD4] text-white' : 'hover:bg-[#F8F8F8]'" v-html="link.label"></Link>
                            <span v-else class="px-3 py-1 border border-[#E5E5E5] rounded-[4px] opacity-50" v-html="link.label"></span>
                        </template>
                    </div>
                </div>
            </div>

        </div>

        <!-- Create / Edit Modal -->
        <Modal :show="isModalOpen" @close="closeModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    {{ editMode ? 'Edit Voucher' : 'Create New Voucher' }}
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="username" value="Username" />
                        <TextInput id="username" type="text" class="mt-1 block w-full" v-model="form.username" required autofocus />
                        <InputError class="mt-2" :message="form.errors.username" />
                    </div>

                    <div>
                        <InputLabel for="password" value="Password" />
                        <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div v-if="editMode">
                        <InputLabel for="status" value="Status" />
                        <select id="status" v-model="form.status" class="mt-1 block w-full border-[#E5E5E5] focus:border-[#065FD4] focus:ring-[#065FD4] rounded-[4px] shadow-sm text-[14px]">
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="terbeli">Terbeli</option>
                            <option value="belum aktif">Belum Aktif</option>
                            <option value="expired">Expired</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.status" />
                    </div>

                    <div>
                        <InputLabel for="duration_days" value="Paket Durasi Aktif" />
                        <select id="duration_days" v-model="form.duration_days" class="mt-1 block w-full border-[#E5E5E5] focus:border-[#065FD4] focus:ring-[#065FD4] rounded-[4px] shadow-sm text-[14px]" required>
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-2" :message="form.errors.duration_days" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closeModal">Cancel</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            {{ editMode ? 'Save Changes' : 'Create' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Confirmation Modal -->
        <Modal :show="isDeleteModalOpen" @close="closeDeleteModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Delete Voucher
                </h2>
                <p class="text-sm text-gray-600">
                    Are you sure you want to delete this voucher? This action cannot be undone.
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <SecondaryButton @click="closeDeleteModal">Cancel</SecondaryButton>
                    <DangerButton @click="deleteVoucher">Delete</DangerButton>
                </div>
            </div>
        </Modal>

        <!-- Bulk Generate Modal -->
        <Modal :show="isBulkModalOpen" @close="closeBulkModal">
            <div class="p-6 font-['Roboto']">
                <h2 class="text-lg font-medium text-gray-900 mb-6">
                    Bulk Generate Vouchers
                </h2>

                <form @submit.prevent="submitBulk" class="space-y-4">
                    <div>
                        <InputLabel for="bulk_count" value="Jumlah Voucher (Max 100)" />
                        <TextInput id="bulk_count" type="number" min="1" max="100" class="mt-1 block w-full" v-model="bulkForm.count" required autofocus />
                        <InputError class="mt-2" :message="bulkForm.errors.count" />
                    </div>

                    <div>
                        <InputLabel for="bulk_duration_days" value="Paket Durasi Aktif" />
                        <select id="bulk_duration_days" v-model="bulkForm.duration_days" class="mt-1 block w-full border-[#E5E5E5] focus:border-[#065FD4] focus:ring-[#065FD4] rounded-[4px] shadow-sm text-[14px]" required>
                            <option value="7">1 Minggu (7 Hari)</option>
                            <option value="14">2 Minggu (14 Hari)</option>
                            <option value="21">3 Minggu (21 Hari)</option>
                            <option value="30">1 Bulan (30 Hari)</option>
                        </select>
                        <InputError class="mt-2" :message="bulkForm.errors.duration_days" />
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <SecondaryButton @click="closeBulkModal">Cancel</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': bulkForm.processing }" :disabled="bulkForm.processing">
                            Generate
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
