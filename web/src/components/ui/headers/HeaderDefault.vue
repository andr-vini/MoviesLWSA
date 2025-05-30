<script setup>
    import { useAsideNavStore } from '@store/asideNav'
    import { useAuthStore } from '@store/auth'
    import { useRouter } from 'vue-router'
    import ButtonDefaultCancel from '@components/ui/buttons/ButtonDefaultCancel.vue'

    const asideNavStore = useAsideNavStore();
    const authStore = useAuthStore();
    const router = useRouter();

    const handleLogout = () => {
        authStore.logout();
        router.push('/login');
    };
</script>

<template>
    <div class="gap-3 px-5 min-h-[50px] flex items-center border-b border-b-gray-200 w-full">
        <span class="cursor-pointer" @click="asideNavStore.toggle()" v-show="asideNavStore.isCollapsed">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </span>
        <div class="flex justify-between items-center w-full">
            <h1>Bem-vindo, {{ authStore.user?.name }}!</h1>
            <ButtonDefaultCancel @click="handleLogout"> Sair </ButtonDefaultCancel>
        </div>
    </div>
    
</template>