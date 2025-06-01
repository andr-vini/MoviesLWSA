<?php

namespace App\Repositories;

use App\Models\Favorite;
use App\Models\User;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;

class FavoriteRepository
{
    public function create(array $data): Favorite
    {
        return Favorite::create($data);
    }

    public function getFavoritesByUser(User $user)
    {
        return $user->favoritesMovies;
    }

    public function delete(Movie $movie): bool
    {
        return Auth::user()->favoritesMovies()->detach($movie->id);
    }
    public function exists(int $userId, int $movieId): bool
    {
        return Favorite::where('user_id', $userId)->where('movie_id', $movieId)->exists();
    }
}
