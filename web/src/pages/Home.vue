<script setup>
    import { ref, watch } from 'vue'
    import { tmdbService } from '@services/tmdb.js'
    import { useToast } from 'vue-toastification'
    import LayoutDefault from '@components/layouts/LayoutDefault.vue'
    import InputDefault from '@components/inputs/InputDefault.vue'
    import ButtonDefaultConfirm from '@components/ui/buttons/ButtonDefaultConfirm.vue'
    import ButtonDefaultCancel from '@components/ui/buttons/ButtonDefaultCancel.vue'
    import MovieGrid from '@components/movies/MovieGrid.vue'

    const toast = useToast()
    const searchQuery = ref('');
    const movies = ref([])
    const isLoading = ref(false)
    const currentPage = ref(0)
    const totalResults = ref(0)
    const totalPages = ref(0)

    async function sendRequest() {
        isLoading.value = true
        try {
            const response = await tmdbService.searchMovie(searchQuery.value, currentPage.value)
            movies.value = response.movies
            totalResults.value = response.totalResults
            totalPages.value = response.totalPages
            console.log(currentPage.value)
        } catch (error) {
            toast.error(error.message)
            movies.value = []
        } finally {
            isLoading.value = false
        }
    }
    const handleSearch = async (event) => {
        currentPage.value = 1
        sendRequest()
    }

    watch(currentPage, async (newPage, oldPage) => {
        if (newPage !== oldPage && searchQuery.value.trim()) {
            sendRequest()
        }
    })
    
    const previousPage = () => {
        if (currentPage.value > 1) {
            currentPage.value--
        }
    }

    const nextPage = () => {
        if (currentPage.value < totalPages.value) {
            currentPage.value++
        }
    }
</script>

<template>
    <LayoutDefault>
        <div class="bg-slate-100 px-4 py-5 h-full">
            <div class="grid grid-cols-12 gap-4 mb-5">
                <div class="col-span-12 md:col-span-10">
                    <InputDefault placeholder="Digite o nome do filme para buscar" v-model="searchQuery"/>
                </div>
                <div class="col-span-12 md:col-span-2">
                    <ButtonDefaultConfirm class="w-full h-full " @click="handleSearch">Pesquisar</ButtonDefaultConfirm>
                </div>
            </div>
            <div class="text-center my-5">
                <span v-if="isLoading">
                    Procurando filmes...
                </span>
                <span v-if="totalResults > 0">
                {{ totalResults }} resultados encontrados
                </span>
            </div>
            <MovieGrid :movies="movies"/>
            <div class="flex justify-center gap-4 mt-10">
                <ButtonDefaultCancel @click="previousPage" v-show="currentPage > 1">Página Anterior</ButtonDefaultCancel>
                <ButtonDefaultConfirm @click="nextPage" v-show="currentPage < totalPages">Próxima Página</ButtonDefaultConfirm>
            </div>
        </div>
    </LayoutDefault>
</template>