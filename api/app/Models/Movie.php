<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'id_tmdb',
        'title',
        'poster_path',
        'release_date',
        'overview',
        'genre_ids_tmdb'
    ];

    protected function casts(): array
    {
        return [
            'genre_ids_tmdb' => 'array'
        ];
    }
}
