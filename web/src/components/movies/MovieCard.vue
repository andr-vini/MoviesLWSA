<script setup>
import { computed } from 'vue'
import { useFavoritesStore } from '@stores/favorites.js'
import imageNotFound from '@/assets/image-not-found.png'

const favoritesStore = useFavoritesStore();

const props = defineProps({
    movie: { type: Object, required: true }
})

const posterUrl = computed(() => {
    return props.movie.poster_path
        ? `https://image.tmdb.org/t/p/w300${props.movie.poster_path}`
        : imageNotFound
})

const handleToggleFavorite = async () => {
    await favoritesStore.toggleFavorite(props.movie)
}

const isFavorited = computed(() => {
    return favoritesStore.isFavorite(props.movie.id_tmdb || props.movie.id)
})

const releaseDate = computed(() => {
    if (props.movie.release_date) {
        const [year, month, day] = props.movie.release_date.split('-');
        return `${day}/${month}/${year}`;
    }
    return 'Sem data';
}) 
</script>
<template>
    <div class="relative">
        <div class="group relative cursor-pointer" @click="handleToggleFavorite">
            <img class="w-40" :src="posterUrl" alt="">
            <div
                class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-70 transition duration-300 flex items-center justify-center">
                <span class="text-white text-sm">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="#66E0C5" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path
                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                        </path>
                    </svg>

                </span>
            </div>
        </div>

        <div class=" text-center">
            <h5 class="text-sm mt-2">
                {{ movie.title }}
            </h5>
            <span class="text-sm">( {{ releaseDate }} )</span>
        </div>
        <div class="top-0 right-0 cursor-pointer p-1 absolute rounded-full" v-if="isFavorited">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="#FE251B" stroke-linecap="round"
                stroke-linejoin="round">
                <path
                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                </path>
            </svg>
        </div>
    </div>
</template>