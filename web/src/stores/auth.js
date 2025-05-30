import { defineStore } from 'pinia'
import { authService } from '../services/auth.js'
import { useToast } from 'vue-toastification'

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
                throw error
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
                console.log(user)
                return user
            } catch (error) {
                // Se não conseguir buscar o usuário, é porque o token é inválido
                toast.error('Tempo de sessão expirado, faça login novamente');
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
                    // Se falhar, remove o token inválido
                    toast.error('Tempo de sessão expirado, faça login novamente');
                    // console.error('Token inválido, fazendo logout:', error)
                    this.logout()
                    setTimeout(() => {
                        window.location.href = '/login'
                    }, 2000)
                }
            }
        }
    }
})