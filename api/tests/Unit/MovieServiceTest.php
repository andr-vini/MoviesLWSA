<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\MovieService;
use App\Repositories\MovieRepository;
use App\Repositories\FavoriteRepository;
use App\Models\Movie;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Mockery;

class MovieServiceTest extends TestCase
{
    protected $movieService;
    protected $movieRepository;
    protected $favoriteRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->movieRepository = Mockery::mock(MovieRepository::class);
        $this->favoriteRepository = Mockery::mock(FavoriteRepository::class);
        $this->movieService = new MovieService($this->movieRepository, $this->favoriteRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_creates_new_movie_when_not_exists()
    {
        $movieData = [
            'id_tmdb' => 123456,
            'title' => 'Test Movie',
            'overview' => 'A great test movie'
        ];

        $movie = new Movie($movieData);
        $movie->id = 1;

        $this->movieRepository
            ->shouldReceive('getMovieByTmdbId')
            ->once()
            ->with($movieData['id_tmdb'])
            ->andReturn(null);

        $this->movieRepository
            ->shouldReceive('create')
            ->once()
            ->with($movieData)
            ->andReturn($movie);

        $result = $this->movieService->store($movieData);

        $this->assertEquals($movie, $result);
    }

    public function test_store_updates_existing_movie()
    {
        $movieData = [
            'id_tmdb' => 123456,
            'title' => 'Updated Movie Title',
            'overview' => 'Updated overview'
        ];

        $existingMovie = new Movie([
            'id_tmdb' => 123456,
            'title' => 'Old Title',
            'overview' => 'Old overview'
        ]);
        $existingMovie->id = 1;

        $this->movieRepository
            ->shouldReceive('getMovieByTmdbId')
            ->once()
            ->with($movieData['id_tmdb'])
            ->andReturn($existingMovie);

        $this->movieRepository
            ->shouldReceive('update')
            ->once()
            ->with($existingMovie, $movieData)
            ->andReturn($existingMovie);

        $result = $this->movieService->store($movieData);

        $this->assertEquals($existingMovie, $result);
    }

    public function test_add_favorite_creates_favorite_when_not_exists()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $movie = new Movie();
        $movie->id = 1;

        $favorite = new Favorite();
        $favorite->id = 1;

        $data = [
            'user_id' => $user->id,
            'movie_id' => $movie->id
        ];

        $this->favoriteRepository
            ->shouldReceive('exists')
            ->once()
            ->with($user->id, $movie->id)
            ->andReturn(false);

        $this->favoriteRepository
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn($favorite);

        $result = $this->movieService->addFavorite($movie);

        $this->assertEquals($favorite, $result);
    }

    public function test_add_favorite_throws_exception_when_already_favorited()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('id')->andReturn($user->id);

        $movie = new Movie();
        $movie->id = 1;

        $this->favoriteRepository
            ->shouldReceive('exists')
            ->once()
            ->with($user->id, $movie->id)
            ->andReturn(true);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Erro ao favoritar filme: Filme já está favoritado.');

        $this->movieService->addFavorite($movie);
    }

    public function test_remove_favorite_successfully_removes_favorite()
    {
        $movieTmdbId = 123456;
        $movie = new Movie();
        $movie->id = 1;

        $this->movieRepository
            ->shouldReceive('getMovieByTmdbId')
            ->once()
            ->with($movieTmdbId)
            ->andReturn($movie);

        $this->favoriteRepository
            ->shouldReceive('delete')
            ->once()
            ->with($movie)
            ->andReturn(true);

        $result = $this->movieService->removeFavorite($movieTmdbId);

        $this->assertTrue($result);
    }

    public function test_remove_favorite_throws_exception_when_movie_not_found()
    {
        $movieTmdbId = 999999;

        $this->movieRepository
            ->shouldReceive('getMovieByTmdbId')
            ->once()
            ->with($movieTmdbId)
            ->andThrow(new \Exception('Movie not found'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Erro ao remover filme dos favoritos');

        $this->movieService->removeFavorite($movieTmdbId);
    }

    public function test_get_all_favorites_returns_user_favorites()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);

        $favorites = collect([
            new Movie(['title' => 'Movie 1']),
            new Movie(['title' => 'Movie 2'])
        ]);

        $this->favoriteRepository
            ->shouldReceive('getFavoritesByUser')
            ->once()
            ->with($user)
            ->andReturn($favorites);

        $result = $this->movieService->getAllFavorites();

        $this->assertEquals($favorites, $result);
    }

    public function test_get_all_favorites_throws_exception_when_repository_fails()
    {
        $user = User::factory()->create();
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('id')->andReturn($user->id);

        $this->favoriteRepository
            ->shouldReceive('getFavoritesByUser')
            ->once()
            ->with($user)
            ->andThrow(new \Exception('Database error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Erro ao listar filmes');

        $this->movieService->getAllFavorites();
    }
}
