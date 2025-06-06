<script setup>
import { ref, watch } from 'vue'
import { tmdbService } from '@services/tmdb.js'
import { useToast } from 'vue-toastification'
import { ButtonDefaultConfirm, ButtonDefaultCancel } from '@components/ui/buttons'
import { MovieGrid } from '@components/movies'
import { InputDefault } from '@components/inputs'
import LayoutDefault from '@components/layouts/LayoutDefault.vue'

const toast = useToast()
const searchQuery = ref('');
const movies = ref([])
const isLoading = ref(false)
const currentPage = ref(1)
const totalResults = ref(0)
const totalPages = ref(0)

const handleSearch = async () => {
    if (searchQuery.value.trim() != '') {
        isLoading.value = true
        try {
            const response = await tmdbService.searchMovie(searchQuery.value, currentPage.value)
            movies.value = response.movies
            totalResults.value = response.totalResults
            totalPages.value = response.totalPages
        } catch (error) {
            toast.warning(error.message)
            totalResults.value = 0
            movies.value = []
        } finally {
            isLoading.value = false
        }
    }
}

watch(searchQuery, (newQuery, oldQuery) => {
    if (newQuery !== oldQuery && currentPage.value !== 1) {
        currentPage.value = 1;
    }
})

const previousPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
        handleSearch()
    }
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
        handleSearch()
    }
}
</script>

<template>
    <LayoutDefault>
        <div class="bg-slate-100 px-4 py-5 h-full">
            <div class="grid grid-cols-12 gap-4 mb-5">
                <div class="col-span-12 md:col-span-10">
                    <InputDefault placeholder="Digite o nome do filme para buscar" v-model="searchQuery" />
                </div>
                <div class="col-span-12 md:col-span-2">
                    <ButtonDefaultConfirm class="w-full h-full " @click="handleSearch">Pesquisar</ButtonDefaultConfirm>
                </div>
            </div>
            <div class="text-center my-5">
                <span v-if="isLoading">
                    Procurando filmes...
                </span>
                <span v-show="!isLoading && searchQuery">
                    {{ totalResults }} resultados encontrados
                </span>
            </div>
            <MovieGrid :movies="movies" />
            <div class="flex justify-center gap-4 mt-10" v-if="totalResults > 0">
                <ButtonDefaultCancel @click="previousPage" v-show="currentPage > 1">Página Anterior
                </ButtonDefaultCancel>
                <ButtonDefaultConfirm @click="nextPage" v-show="currentPage < totalPages">Próxima Página
                </ButtonDefaultConfirm>
            </div>
        </div>
    </LayoutDefault>
</template>