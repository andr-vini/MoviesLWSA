<script setup>
    import { ref, markRaw } from 'vue'
    import { computed } from 'vue'
    import { useRoute, useRouter } from 'vue-router'
    import { useAuthStore } from '@store/auth'
    import { useAsideNavStore } from '@store/asideNav'
    import HomeIcon from '@components/icons/HomeIcon.vue'

    const asideOpen = ref(true);
    const asideNavStore = useAsideNavStore();
    const route = useRoute();

    const navItems = [
        {
            name: 'Buscar Filmes',
            to: '/',
            icon: markRaw(HomeIcon)
        },
        {
            name: 'Favoritos',
            to: '/favoritos',
            icon: 'teste'
        }
    ];

    const isActive = (routePath) => {
        return route.path === routePath;
    }
</script>

<template>
    <div :class="['space-y-3 border-r border-r-gray-200 min-h-screen overflow-hidden transition-all duration-200', asideNavStore.sidebarWidth]">
        <header class="px-4 min-h-[50px] flex justify-between items-center border-b border-b-gray-200">
            <h1 class="text-green-500 text-xl font-semibold">Movies</h1>
            <span class="cursor-pointer" @click="asideNavStore.toggle()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </span>
        </header>
        <div class="px-4">
            <nav>
                <ul class="space-y-2">
                    <li v-for="item in navItems" :key="item.name">
                        <router-link :to="item.to" :class="['whitespace-nowrap flex px-3 py-2 rounded-md hover:ml-3 hover:p-3 transition-all duration-200', isActive(item.to) ? 'bg-green-500 text-white' : 'bg-gray-200']">
                            <div class="flex gap-2">
                                <component :is="item.icon" class="w-5 h-5" />
                                <span> {{ item.name }} </span>
                            </div>
                        </router-link>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>