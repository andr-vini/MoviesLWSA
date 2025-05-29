import { defineStore } from 'pinia'
import { authService } from '../services/auth.js'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('token'),
        isLoading: false
    }),
    
    getters: {
        isAuthenticated: (state) => !!state.token,
        isGuest: (state) => !state.token
    },
    
    actions: {
        async login(credentials) {
            this.isLoading = true
            try {
                const response = await authService.login(credentials)
                this.token = response.access_token
                this.user = response.user
                authService.setToken(response.access_token)
                return response
            } catch (error) {
                throw error
            } finally {
                this.isLoading = false
            }
        },
        
        async register(credentials) {
            this.isLoading = true
            try {
                const response = await authService.register(credentials)
                this.token = response.access_token
                this.user = response.user
                authService.setToken(response.access_token)
                return response
            } catch (error) {
                throw error
            } finally {
                this.isLoading = false
            }
        },
        
        logout() {
            authService.logout()
            this.token = null
            this.user = null
        },
        
        initAuth() {
            this.token = localStorage.getItem('token')
        }
    }
})