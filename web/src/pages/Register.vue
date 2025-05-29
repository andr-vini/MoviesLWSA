<script setup>
    import CardDefault from '@components/ui/cards/CardDefault.vue'
    import RegisterForm from '@components/forms/RegisterForm.vue'
</script>

<template>
    <div class="sm:max-w-80/100 mx-auto">
        <header class="min-h-10 border-b-gray-200 border-b py-5">
            <h2 class="text-center sm:text-left text-2xl text-green-600 font-semibold">Movies</h2>
        </header>
        <div class="mt-10">
            <div class="text-center">
                <h1 class="text-3xl font-bold">Movies</h1>
                <h3 class="text-lg text-gray-500">Gerencie seus filmes favoritos aqui!</h3>
            </div>
            <div class="px-10 sm:px-0 mt-7">
                <CardDefault customClass="mx-auto max-w-[500px]">
                    <RegisterForm/>
                    <span>Já tem uma conta? </span><router-link to="/login">Entre</router-link>
                </CardDefault>
            </div>
        </div>
    </div>
</template>