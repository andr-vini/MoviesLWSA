<script setup>
    import { ref } from 'vue';
    import { useAuthStore } from '../../stores/auth'
    import { useRouter } from 'vue-router'
    import { useToast } from 'vue-toastification'

    import InputDefault from '@components/inputs/InputDefault.vue'
    import InputPassword from '@components/inputs/InputPassword.vue';
    import ButtonDefaultConfirm from '@components/ui/buttons/ButtonDefaultConfirm.vue';

    const email = ref('')
    const password = ref('')
    const name = ref('')
    const authStore = useAuthStore()
    const router = useRouter()
    const toast = useToast()

    const handleSubmit = async (event) => {
        try {
            await authStore.register({
                email: email.value,
                password: password.value,
                name: name.value
            });
            
            toast.success('Cadastro realizado com sucesso!');
            router.push('/');
            
        } catch (error) {
            toast.error(error.message || 'Erro no cadastro');
        }
    }
</script>

<template>
    <form @submit.prevent="handleSubmit">
        <div class="space-y-4">
            <div>
                <InputDefault required type="email" v-model="email" placeholder="yourbestmail@gmail.com"/>
            </div>
            <div>
                <InputDefault required type="text" v-model="name" placeholder="Digite seu nome"/>
            </div>
            <div>
                <InputPassword required v-model="password"/>
            </div>
            <div>
                <ButtonDefaultConfirm customClass="w-full py-2" :type="'submit'"> Salvar </ButtonDefaultConfirm>
            </div>
        </div>
    </form>
</template>