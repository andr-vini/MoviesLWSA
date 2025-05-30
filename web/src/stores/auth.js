import { defineStore } from 'pinia'
import { authService } from '../services/auth.js'
import { useToast } from 'vue-toastification'
// import router from '../router'

const toast = useToast()

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
                const message = error.status === 401 
                    ? 'Email ou senha incorretos' 
                    : 'Erro no servidor. Tente novamente.'
                throw new Error(message)
            } finally {
                this.isLoading = false
            }
        },
        
        async fetchUser() {
            if (!this.token) return null
            
            try {
                this.isLoading = true
                const user = await authService.getCurrentUser()
                this.user = user
                return user
            } catch (error) {
                this.logout()
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
        
        async initAuth() {
            const token = localStorage.getItem('token')
            this.token = token
            
            if (token) {
                try {
                    await this.fetchUser()
                } catch (error) {
                    toast.error('Sessão expirada, faça login novamente')
                    this.logout()
                    
                    setTimeout(() => {
                        window.location.href = '/login'
                    }, 2000)
                }
            }
        }
    }
})