<script setup>
    import { useRouter } from 'vue-router'
    import { useAsideNavStore } from '@store/asideNav'
    import { onMounted, onUnmounted } from 'vue'
    import AsideNav from '@components/ui/asides/AsideNav.vue'
    import HeaderDefault from '@components/ui/headers/HeaderDefault.vue'

    const asideNavStore = useAsideNavStore();
    
    // Função para detectar tamanho da tela
    const handleResize = () => {
        const isMobile = window.innerWidth < 1024 // lg breakpoint do Tailwind
        asideNavStore.setMobile(isMobile)
    }

    // Detectar escape key para fechar sidebar em mobile
    const handleEscapeKey = (event) => {
        if (event.key === 'Escape' && asideNavStore.isMobile && asideNavStore.isOpen) {
            asideNavStore.collapse()
        }
    }

    onMounted(() => {
        // Verificar tamanho inicial
        handleResize()
        
        // Adicionar listeners
        window.addEventListener('resize', handleResize)
        document.addEventListener('keydown', handleEscapeKey)
    })

    onUnmounted(() => {
        // Remover listeners
        window.removeEventListener('resize', handleResize)
        document.removeEventListener('keydown', handleEscapeKey)
    })

</script>

<template>
    <div class="flex">
        <AsideNav/>
        <div :class="[!asideNavStore.isCollapsed ? 'w-[0px] md:w-full' : 'w-full']">
            <HeaderDefault/>
            <slot></slot>
        </div>
    </div>
</template>