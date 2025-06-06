import api from './api.js'

export const authService = {
	async login(credentials) {
		try {
			const response = await api.post('/login', credentials)
			return response.data
		} catch (error) {
			throw error?.response || error
		}
	},

	async getCurrentUser() {
		try {
			const response = await api.get('/user')
			return response.data
		} catch (error) {
			throw error?.response || error
		}
	},
	
	async register(credentials) {
		try{
			const response = await api.post('/register', credentials)
			return response.data
		} catch (error) {
			throw error?.response || error
		}
	},

	async logout() {
		try {
			await api.post('/logout')
			localStorage.removeItem('token')
		} catch (error) {
			localStorage.removeItem('token')
		}
	},

	isAuthenticated() {
		return !!localStorage.getItem('token')
	},

	setToken(token) {
		localStorage.setItem('token', token)
	},

	removeToken() {
		localStorage.removeItem('token')
	},
}