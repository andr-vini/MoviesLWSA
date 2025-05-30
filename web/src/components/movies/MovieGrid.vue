<script setup>
    import MovieCard from '@components/movies/MovieCard.vue'
    defineProps({
        movies: { type: Array, default: () => []},
    })
</script>
<template>
    <div class="flex flex-wrap gap-8 justify-center">
        <div v-for="movie in movies" :key="movie.id" class="max-w-40">
            <MovieCard :movie="movie"/>
        </div>
    </div>
</template>