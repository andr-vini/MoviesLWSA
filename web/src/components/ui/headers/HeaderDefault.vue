<script setup>
import { useAsideNavStore } from '@stores/asideNav'
import { useAuthStore } from '@stores/auth'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import { useFavoritesStore } from '@stores/favorites'
import ButtonDefaultCancel from '@components/ui/buttons/ButtonDefaultCancel.vue'

const asideNavStore = useAsideNavStore();
const authStore = useAuthStore();
const router = useRouter();
const toast = useToast()
const favoritesStore = useFavoritesStore();

const handleLogout = async () => {
    try {
        await authStore.logout();
        toast.success('Você encerrou sua sessão!')
    } finally {
        router.push('/login');
    }
};
</script>

<template>
    <div class="gap-3 px-5 min-h-[50px] flex items-center border-b border-b-gray-200 w-full">
        <span class="cursor-pointer" @click="asideNavStore.toggle()" v-show="asideNavStore.isCollapsed">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </span>
        <div class="flex justify-between items-center w-full">
            <h1 class="text-nowrap">Bem-vindo, {{ authStore.user?.name }}!</h1>
            <div class="flex">
                <div class="cursor-pointer p-1 rounded-full right-5 top-0.5 relative">
                    <router-link to="/favorites">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="#FE251B" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                            </path>
                        </svg>
                        <span
                            class="absolute -top-1 -right-1 text-xs bg-green-500 p-0.5 px-1.5 rounded-full text-white">
                            {{ favoritesStore.favorites.length }}
                        </span>
                    </router-link>
                </div>
                <ButtonDefaultCancel @click="handleLogout"> Sair </ButtonDefaultCancel>
            </div>
        </div>
    </div>

</template>