import { defineStore } from 'pinia'
import { authService } from '@services/auth.js'
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
                let message = '';
                switch (error.status) {
                    case 401:
                        message = 'Email ou senha incorretos';
                        break;
                    case 422:
                        message = error.data?.message;
                        break;
                    default:
                        message = 'Erro no servidor. Tente novamente mais tarde.';
                        break;
                }
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
                await this.logout()
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
                const message = error.status === 422 ? error.data?.message : 'Ocorreu um erro no servidor, tente novamente mais tarde';
                throw new Error(message)
            } finally {
                this.isLoading = false
            }
        },
        
        async logout() {
            await authService.logout()
            this.token = null
            this.user = null
        },
        
        async initAuth() {
            if (this.token) {
                try {
                    await this.fetchUser()
                } catch (error) {
                    toast.error('Sessão expirada, faça login novamente')
                    await this.logout()
                    window.location.href = '/login'
                }
            }
        }
    }
})