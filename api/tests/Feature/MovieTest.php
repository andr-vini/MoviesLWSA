<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Movie;

class MovieTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private function authenticateUser()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function test_authenticated_user_can_add_movie_to_favorites()
    {
        $auth = $this->authenticateUser();

        $movieData = [
            'id_tmdb' => 123456,
            'title' => 'Test Movie',
            'overview' => 'A great test movie',
            'poster_path' => '/test-poster.jpg',
            'release_date' => '2023-01-01',
            'genre_ids_tmdb' => [28, 12]
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->postJson('/api/favorite', $movieData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('movies', [
            'id_tmdb' => 123456,
            'title' => 'Test Movie'
        ]);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $auth['user']->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_add_movie_to_favorites()
    {
        $movieData = [
            'id_tmdb' => 123456,
            'title' => 'Test Movie',
            'overview' => 'A great test movie',
            'poster_path' => '/test-poster.jpg',
            'release_date' => '2023-01-01',
            'genre_ids_tmdb' => [28, 12]
        ];

        $response = $this->postJson('/api/favorite', $movieData);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_cannot_add_invalid_movie_to_favorites()
    {
        $auth = $this->authenticateUser();

        $movieData = [
            'title' => 'Test Movie',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->postJson('/api/favorite', $movieData);

        $response->assertStatus(422);
    }

    public function test_authenticated_user_can_get_favorite_movies()
    {
        $auth = $this->authenticateUser();

        $movie1 = Movie::factory()->create(['id_tmdb' => 111]);
        $movie2 = Movie::factory()->create(['id_tmdb' => 222]);

        $auth['user']->favoritesMovies()->attach([$movie1->id, $movie2->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->getJson('/api/favorites');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    public function test_unauthenticated_user_cannot_get_favorite_movies()
    {
        $response = $this->getJson('/api/favorites');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_remove_movie_from_favorites()
    {
        $auth = $this->authenticateUser();

        $movie = Movie::factory()->create(['id_tmdb' => 123456]);
        $auth['user']->favoritesMovies()->attach($movie->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $auth['user']->id,
            'movie_id' => $movie->id
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->deleteJson('/api/favorite/' . $movie->id_tmdb);

        $response->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $auth['user']->id,
            'movie_id' => $movie->id
        ]);
    }

    public function test_unauthenticated_user_cannot_remove_movie_from_favorites()
    {
        $movie = Movie::factory()->create(['id_tmdb' => 123456]);

        $response = $this->deleteJson('/api/favorite/' . $movie->id_tmdb);

        $response->assertStatus(401);
    }

    public function test_authenticated_user_cannot_remove_nonexistent_movie_from_favorites()
    {
        $auth = $this->authenticateUser();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $auth['token'],
        ])->deleteJson('/api/favorite/999999');

        $response->assertStatus(500)
            ->assertJson([
                'error_message' => 'Ocorreu um erro ao tentar remover o filme dos favoritos'
            ]);
    }

    public function test_user_favorites_are_isolated_between_users()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $token1 = $user1->createToken('test-token')->plainTextToken;
        $token2 = $user2->createToken('test-token')->plainTextToken;

        $movie1 = Movie::factory()->create(['id_tmdb' => 111]);
        $movie2 = Movie::factory()->create(['id_tmdb' => 222]);

        $user1->favoritesMovies()->attach($movie1->id);

        $user2->favoritesMovies()->attach($movie2->id);

        $response1 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token1,
        ])->getJson('/api/favorites');

        $response1->assertStatus(200)
            ->assertJsonCount(1);

        $response2 = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token2,
        ])->getJson('/api/favorites');

        $response2->assertStatus(200)
            ->assertJsonCount(1);
    }
}
