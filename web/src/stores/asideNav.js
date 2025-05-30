import { defineStore } from 'pinia'

export const useAsideNavStore = defineStore('sidebar', {
    state: () => ({
       isCollapsed: false,
       isMobile: false 
    }),

    getters: {
        sidebarWidth: (state) => state.isCollapsed ? 'w-[10px]' : 'w-full md:w-[300px]'
    },

    actions: {
        toggle() {
            this.isCollapsed = !this.isCollapsed
        },

        setMobile(isMobile) {
            this.isMobile = isMobile

            if (isMobile) {
                this.isCollapsed = true
            }
        }
    }
})