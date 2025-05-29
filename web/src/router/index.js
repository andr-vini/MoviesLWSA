import { createRouter, createWebHistory } from "vue-router"
import { useAuthStore } from "../stores/auth"
import { useToast } from 'vue-toastification'

import Home from "../pages/Home.vue"
import Login from "../pages/Login.vue"
import Register from "../pages/Register.vue"

const routes = [
    { path: '/login', name: 'Login', component: Login, meta: { guest: true }},
    { path: '/register', name: 'register', component: Register, meta: { guest: true }},
    { path: '/', name: 'Home', component: Home, meta: { requiresAuth: true }},
];

const toast = useToast()

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    // Rotas que requerem autenticação
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        toast.warning('Você precisa estar logado para acessar essa rota')
        next({ name: 'Login'});
        return;
    }
    
    // Rotas apenas para guests (não logados)
    if (to.meta.guest && authStore.isAuthenticated) {
        next('/');
        return;
    }
    
    next();
});

export default router;