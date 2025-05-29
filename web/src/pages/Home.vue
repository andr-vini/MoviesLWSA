<script setup>
    import { useAuthStore } from '../stores/auth'
    import { useRouter } from 'vue-router'

    const authStore = useAuthStore();
    const router = useRouter();

    const handleLogout = () => {
        authStore.logout();
        router.push('/login');
    };
</script>

<template>
    <div>
        <h1>Bem-vindo, {{ authStore.user?.name }}!</h1>
        <button @click="handleLogout">Sair</button>
    </div>
</template>