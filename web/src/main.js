import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia'
import { useAuthStore } from './stores/auth'

import Toast from 'vue-toastification'
import 'vue-toastification/dist/index.css'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)

// Inicializar autenticação
const authStore = useAuthStore()
authStore.initAuth()

app.use(Toast, {
  position: "top-right",
  timeout: 5000,
  closeOnClick: true,
  pauseOnFocusLoss: false,
  pauseOnHover: false,
  draggable: true,
  draggablePercent: 0.6,
  showCloseButtonOnHover: false,
  hideProgressBar: true,
  closeButton: "button",
  icon: true,
  rtl: false
})

app.mount('#app')