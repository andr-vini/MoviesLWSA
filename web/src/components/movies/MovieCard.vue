<script setup>
    import { computed } from 'vue'
    import imageNotFound from '@/assets/image-not-found.png'

    const props = defineProps({
        movie: { type: Object, required: true }
    })

    const posterUrl = computed(() => {
        return props.movie.poster_path 
            ? `https://image.tmdb.org/t/p/w300${props.movie.poster_path}`
            : imageNotFound
    })
</script>
<template>
    <div class="relative">
        <div>
            <img class="w-40" :src="posterUrl" alt="">
        </div>
        <div>
            <h5 class="text-sm text-center mt-2">
            {{ movie.title }}
            </h5>
        </div>
        <div class="top-0 right-0 cursor-pointer p-1 absolute rounded-full">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="#66E0C5" stroke-linecap="round" stroke-linejoin="round" >
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
        </div>

    </div>
</template>