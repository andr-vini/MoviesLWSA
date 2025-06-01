<script setup>
import { useAsideNavStore } from '@stores/asideNav'
import { onMounted, onUnmounted } from 'vue'
import { useFavoritesStore } from '@stores/favorites'
import AsideNav from '@components/ui/asides/AsideNav.vue'
import HeaderDefault from '@components/ui/headers/HeaderDefault.vue'

const asideNavStore = useAsideNavStore();
const favoritesStore = useFavoritesStore();

const handleResize = () => {
    const isMobile = window.innerWidth < 1024
    asideNavStore.setMobile(isMobile)
}

onMounted(async () => {
    handleResize()
    window.addEventListener('resize', handleResize)

    try {
        await favoritesStore.initializeFavorites()
    } catch (error) {
        console.error('Erro ao carregar favoritos:', error)
    }
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
})

</script>

<template>
    <div class="flex">
        <AsideNav />
        <div :class="['transition-all', !asideNavStore.isCollapsed ? 'w-[0px] overflow-hidden md:w-full' : 'w-full']">
            <HeaderDefault />
            <slot></slot>
        </div>
    </div>
</template>