<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="max-w-2xl">
        <header class="flex items-start gap-3.5 mb-6">
            <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center font-bold text-base shrink-0 mt-0.5">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                    Zona Bahaya: Hapus Akun
                </h2>
                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">
                    Setelah akun Anda dihapus, semua data profil, riwayat pembelian, dan akses voucher lab Anda akan dihapus secara permanen.
                </p>
            </div>
        </header>

        <div>
            <DangerButton @click="confirmUserDeletion">
                <i class="fa-solid fa-trash-can text-xs"></i>
                <span>Hapus Akun Saya</span>
            </DangerButton>
        </div>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <!-- Modal Header -->
                <div class="flex items-center gap-3.5 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 tracking-tight">
                            Yakin Ingin Menghapus Akun?
                        </h2>
                        <p class="text-xs text-gray-500">
                            Tindakan ini permanen dan tidak dapat dipulihkan kembali.
                        </p>
                    </div>
                </div>

                <p class="text-xs text-gray-600 mb-5 bg-rose-50/50 p-3.5 rounded-xl border border-rose-100 leading-relaxed">
                    Seluruh voucher lab yang sedang aktif atau riwayat transaksi Anda akan hilang dari sistem. Masukkan kata sandi akun Anda untuk mengonfirmasi penghapusan.
                </p>

                <div class="space-y-2">
                    <InputLabel
                        for="delete_password"
                        value="Kata Sandi Akun"
                        class="font-semibold text-xs"
                    />

                    <TextInput
                        id="delete_password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-full rounded-xl border-gray-200 bg-gray-50/50 focus:bg-white focus:border-rose-500 focus:ring-rose-500/20 text-sm"
                        placeholder="Masukkan kata sandi untuk konfirmasi"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="text-xs" />
                </div>

                <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <SecondaryButton @click="closeModal">
                        <i class="fa-solid fa-xmark text-xs"></i>
                        <span>Batal</span>
                    </SecondaryButton>

                    <DangerButton
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        <i class="fa-solid fa-trash-can text-xs"></i>
                        <span>Konfirmasi Hapus Akun</span>
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
