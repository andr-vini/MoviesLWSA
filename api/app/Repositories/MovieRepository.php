<?php

namespace App\Repositories;

use App\Models\Movie;

class MovieRepository
{
    public function create(array $data): Movie
    {
        return Movie::create($data);
    }

    public function update(Movie $movie, array $data): Movie
    {
        $movie->update($data);
        return $movie;
    }

    public function getMovieByTmdbId($id_tmdb): Movie
    {
        return Movie::where('id_tmdb', $id_tmdb)->first();
    }
}
