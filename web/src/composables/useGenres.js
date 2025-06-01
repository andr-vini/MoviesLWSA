import { ref } from 'vue'
import { tmdbService } from '@services/tmdb.js'

export function useGenres() {
	const genres = ref([])
	const loading = ref(false)

	async function fetchGenres() {
		const cached = localStorage.getItem('tmdb_genres')
		const cachedAt = localStorage.getItem('tmdb_genres_cached_at')

		const now = Date.now()
		const timeExpireCache = 24 * 60 * 60 * 1000

		if (cached && cachedAt && (now - cachedAt) < timeExpireCache) {
			genres.value = JSON.parse(cached)
			return
		}

		loading.value = true

		try {
			const response = await tmdbService.getGenres();

			genres.value = response.data.genres
			localStorage.setItem('tmdb_genres', JSON.stringify(response.data.genres))
			localStorage.setItem('tmdb_genres_cached_at', now.toString())

		} catch (error) {
			throw new Error(error);
		} finally {
			loading.value = false
		}
	}

	return {
		genres,
		loading,
		fetchGenres
	}
}