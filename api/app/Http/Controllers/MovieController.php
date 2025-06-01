<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use Illuminate\Http\Request;
use App\Services\MovieService;

class MovieController extends Controller
{
    protected $movieService;
    protected $tmdbService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function setFavorite(StoreMovieRequest $request)
    {
        try {
            $movie = $this->movieService->store($request->all());
            $this->movieService->addFavorite($movie);
            return response()->json($movie, 201);
        } catch (\RuntimeException $e) {
            return response()->json([
                'error_message' => 'Ocorreu um erro ao tentar favoritar o filme'
            ], 500);
        }
    }

    public function removeFavorite($movieTmdbId)
    {
        try {
            $movie = $this->movieService->removeFavorite($movieTmdbId);
        } catch (\RuntimeException $e) {
            return response()->json([
                'error_message' => 'Ocorreu um erro ao tentar remover o filme dos favoritos'
            ], 500);
        }
    }

    public function getFavorites()
    {
        try {
            $favorites = $this->movieService->getAllFavorites();
            return response()->json($favorites, 200);
        } catch (\RuntimeException $e) {
            return response()->json([
                'error_message' => 'Ocorreu um erro ao tentar exibir os filmes favoritos'
            ], 500);
        }
    }
}
