import { defineStore } from "pinia";
import { favoritesService } from "@services/favorites";
import { useToast } from "vue-toastification";

const toast = useToast()

export const useFavoritesStore = defineStore('favorites', {
    state: () => ({
        favorites: [],
        isLoading: false,
    }),

    getters: {
        isFavorite: (state) => (movieId) => {
            return state.favorites.some(favorite => favorite.id_tmdb == movieId);
        }
    },

    actions: {
        async initializeFavorites() {
            if (this.favorites.length === 0) {
                await this.listFavorites();
            }
        },
        
        async toggleFavorite(movie) {
            const movie_id = movie.id_tmdb || movie.id;
            if(this.isFavorite(movie_id)){
                await favoritesService.removeFavorite(movie_id)
                const index = this.favorites.findIndex(fav => fav.id_tmdb == movie_id);
                if(index !== -1) {
                    this.favorites.splice(index, 1);
                }
                toast.success('Filme removido dos favoritos');
            }else{
                const favorite = await favoritesService.addFavorite(movie)
                this.favorites.push(favorite)
                toast.success('Filme adicionado aos favoritos')
            }
        },

        async listFavorites() {
            try{
                const favorites = await favoritesService.listFavorites();
                this.favorites = favorites;
                return favorites;
            } catch (error) {
                toast.error('Erro ao listar filmes favoritos');
                throw new Error(error);
            }

        }
    }
})

