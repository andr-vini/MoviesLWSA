<script setup>
import { ref } from 'vue';
import { useAuthStore } from '@stores/auth'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

import InputDefault from '@components/inputs/InputDefault.vue'
import InputPassword from '@components/inputs/InputPassword.vue'
import ButtonDefaultConfirm from '@components/ui/buttons/ButtonDefaultConfirm.vue'

const email = ref('')
const password = ref('')
const authStore = useAuthStore()
const router = useRouter()
const toast = useToast()

const handleSubmit = async (event) => {
    try {
        await authStore.login({
            email: email.value,
            password: password.value
        });

        router.push('/');
        toast.success('Login realizado com sucesso!');

    } catch (error) {
        toast.error(error.message || 'Erro no login');
    }
}
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <div class="space-y-4">
            <div>
                <InputDefault type="email" required v-model="email" placeholder="yourbestmail@gmail.com" />
            </div>
            <div>
                <InputPassword v-model="password" required />
            </div>
            <div>
                <ButtonDefaultConfirm customClass="w-full py-2" :type="'submit'" :disabled="authStore.isLoading">
                    {{ authStore.isLoading ? 'Enviando...' : 'Enviar' }} </ButtonDefaultConfirm>
            </div>
        </div>
    </form>
</template>