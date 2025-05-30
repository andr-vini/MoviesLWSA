<script setup>
    import { useRouter } from 'vue-router'
    import { useAsideNavStore } from '@store/asideNav'
    import { onMounted, onUnmounted } from 'vue'
    import AsideNav from '@components/ui/asides/AsideNav.vue'
    import HeaderDefault from '@components/ui/headers/HeaderDefault.vue'

    const asideNavStore = useAsideNavStore();
    
    const handleResize = () => {
        const isMobile = window.innerWidth < 1024
        asideNavStore.setMobile(isMobile)
    }

    onMounted(() => {
        handleResize()
        window.addEventListener('resize', handleResize)
    })

    onUnmounted(() => {
        window.removeEventListener('resize', handleResize)
    })

</script>

<template>
    <div class="flex">
        <AsideNav/>
        <div :class="['transition-all', !asideNavStore.isCollapsed ? 'w-[0px] overflow-hidden md:w-full' : 'w-full']">
            <HeaderDefault/>
            <slot></slot>
        </div>
    </div>
</template>