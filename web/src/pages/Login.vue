<script setup>
import CardDefault from '@components/ui/cards/CardDefault.vue'
import { LoginForm } from '@components/forms'
</script>

<template>
    <div class="mx-auto">
        <header class="min-h-10 border-b-gray-200 border-b py-5">
            <h2 class="text-center sm:text-left px-10 text-2xl text-green-600 font-semibold">Movies</h2>
        </header>
        <div class="mt-10">
            <div class="text-center">
                <h1 class="text-3xl font-bold">Movies</h1>
                <h3 class="text-lg text-gray-500">Gerencie seus filmes favoritos aqui!</h3>
            </div>
            <div class="px-10 sm:px-0 mt-7">
                <CardDefault customClass="mx-auto max-w-[500px]">
                    <LoginForm />
                    <div class="text-center mt-2">Não tem conta ainda?<router-link
                            class="text-green-500 hover:decoration-solid hover:underline" to="/register">
                            Registre-se</router-link> </div>
                </CardDefault>
            </div>
        </div>
    </div>
</template>