<?php

namespace App\Services;

use App\Models\Favorite;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MovieRepository;
use App\Repositories\FavoriteRepository;

class MovieService
{

    protected $movieRepository;
    protected $favoriteRepository;

    public function __construct(MovieRepository $movieRepository, FavoriteRepository $favoriteRepository)
    {
        $this->movieRepository = $movieRepository;
        $this->favoriteRepository = $favoriteRepository;
    }

    public function store(array $data): Movie
    {
        try {
            $data['genre_ids_tmdb'] = $data['genre_ids'];
            $movie = Movie::where('id_tmdb', $data['id_tmdb'])->first();
            if (!$movie) {
                $created = $this->movieRepository->create($data);
                return $created;
            }
            $updated = $this->movieRepository->update($movie, $data);
            return $updated;
        } catch (\Exception $e) {
            Log::error('Erro ao criar filme', [
                'error_message' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Erro na criação do filme: ' . $e->getMessage());
        }
    }

    public function addFavorite(Movie $movie): Favorite
    {
        try {
            $userId = Auth::id();
            $data = [
                'user_id' => $userId,
                'movie_id' => $movie->id
            ];

            // Verifica se já existe o favorito
            $exists = $this->favoriteRepository->exists($userId, $movie->id);

            if ($exists) {
                throw new \RuntimeException('Filme já está favoritado.');
            }

            $created = $this->favoriteRepository->create($data);

            return $created;
        } catch (\Exception $e) {
            Log::error('Erro ao favoritar filme', [
                'error_message' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Erro ao favoritar filme: ' . $e->getMessage());
        }

        return $created;
    }

    public function removeFavorite($movieTmdbId)
    {
        try {
            $movie = $this->movieRepository->getMovieByTmdbId($movieTmdbId);
            $deleted = $this->favoriteRepository->delete($movie);
            return $deleted;
        } catch (\Exception $e) {
            Log::error('Erro ao tentar remover dos favoritos', [
                'error_message' => $e->getMessage(),
                'data' => [
                    'user_id' => Auth::id()
                ],
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Erro ao remover filme dos favoritos: ' . $e->getMessage());
        }
    }
    public function getAllFavorites()
    {
        try {
            $favorites = $this->favoriteRepository->getFavoritesByUser(Auth::user());
            return $favorites;
        } catch (\Exception $e) {
            Log::error('Erro ao listar os favoritos', [
                'error_message' => $e->getMessage(),
                'data' => [
                    'user_id' => Auth::id()
                ],
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Erro ao listar filmes: ' . $e->getMessage());
        }
    }
}
