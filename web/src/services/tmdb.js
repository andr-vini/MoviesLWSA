import axios from 'axios'

const apiTmdb = axios.create({
	baseURL: 'https://api.themoviedb.org/3/',
	timeout: 10000,
	
})

apiTmdb.interceptors.request.use(
    (config) => {
        const token = import.meta.env.VITE_TMDB_API_READ_ACCESS_TOKEN
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    (error) => Promise.reject(error)
)

apiTmdb.interceptors.response.use(
    (response) => response,
    (error) => Promise.reject(error)
)
export const tmdbService = {
    checkApiReadAccess() {
        const api_read_access_token = import.meta.env.VITE_TMDB_API_READ_ACCESS_TOKEN
        if (!api_read_access_token) {
            throw new Error('Api Read Access Token não está presente, adicione antes de procurar um filme')
        }

        return api_read_access_token
    },

	async searchMovie(movieName, page) {
        try{
            this.checkApiReadAccess()
            const response = await apiTmdb.get('/search/movie', {
                params: {
                    'query': movieName,
                    'page': page 
                }
            })

            if(response.data.total_results > 0){
                return {
                    movies: response.data.results,
                    totalResults: response.data.total_results,
                    totalPages: response.data.total_pages,
                    currentPage: response.data.page
                }
            } else {
                throw new Error('Nenhum filme encontrado')
            }
        } catch (error) {
            let message = 'Erro na requisição para buscar o filme';

            if (error.response) {
                const status = error.response.status;

                switch (status) {
                    case 401:
                        message = 'Chave de API inválida, insira uma API Key válida';
                        break;
                    case 404:
                        message = 'Recurso não encontrado';
                        break;
                    case 500:
                    case 502:
                    case 503:
                        message = 'Erro interno no servidor do TMDB';
                        break;
                    default:
                        message = error.response.data?.status_message || error.message || message;
                        break;
                }
            } else if (error.request) {
                message = 'Nenhuma resposta recebida do servidor do TMDB';
            } else {
                message = error.message || message;
            }

            throw new Error(message);
            
        }
    },

    async getGenres() {
        try{
            this.checkApiReadAccess();
            const response = await apiTmdb.get('/genre/movie/list?language=pt');
            return response;
        } catch (error) {
            throw error.response || error;
        }
    }
}