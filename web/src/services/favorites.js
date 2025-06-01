import api from './api'

export const favoritesService = {
    async addFavorite(movieData) {
        try{
            const response = await api.post('/favorite', {
                id_tmdb: movieData.id,
                title: movieData.title,
                poster_path: movieData.poster_path,
                release_date: movieData.release_date,
                overview: movieData.overview,
                genre_ids: movieData.genre_ids
            })
            return response.data;
        } catch (error) {
            throw error?.response || error;
        }
    },

    async removeFavorite(movieId) {
        try{
            const response = await api.delete(`/favorite/${movieId}`);
            return response.data;
        } catch (error) {
            throw error?.response || error;
        }
    },

    async listFavorites() {
        try{
            const response = await api.get('/favorites');
            return response.data;
        } catch (error) {
            throw error?.response || error;
        }
    }
}