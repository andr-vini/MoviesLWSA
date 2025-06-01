<script setup>
import { ref, onMounted, computed } from 'vue';
import { useFavoritesStore } from '@stores/favorites.js'
import { MovieGrid } from '@components/movies'
import { useGenres } from '@/composables/useGenres'
import LayoutDefault from '@components/layouts/LayoutDefault.vue'
import { InputSelect } from '../components/inputs';

const { genres, fetchGenres } = useGenres()
const genderSelected = ref('all');

onMounted(() => {
    fetchGenres()
})

const favoriteStore = useFavoritesStore();

const filteredFavorites = computed(() => {
    if (genderSelected.value === 'all') {
        return favoriteStore.favorites;
    }
    return favoriteStore.favorites.filter(fav =>
        fav.genre_ids_tmdb.includes(Number(genderSelected.value))
    );
});
</script>
<template>
    <LayoutDefault>
        <div class="bg-slate-100 px-4 py-5 h-full space-y-5">
            <InputSelect :options="genres" v-model="genderSelected" placeholder="Todos" />
            <MovieGrid :movies="filteredFavorites" />
        </div>
    </LayoutDefault>
</template>