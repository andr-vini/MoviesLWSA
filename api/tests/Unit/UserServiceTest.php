<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Mockery;

class UserServiceTest extends TestCase
{

    protected $userService;
    protected $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_store_creates_user_and_returns_user_with_token()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test_user_service_test@test_user_service_test.com',
            'password' => 'password123'
        ];

        $user = new User($userData);
        $user->id = 1;

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andReturn($user);

        $result = $this->userService->store($userData);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user, $result['user']);
        $this->assertIsString($result['token']);
    }

    public function test_store_throws_exception_when_repository_fails()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $this->userRepository
            ->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andThrow(new \Exception('Database error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Erro na criação do usuário');

        $this->userService->store($userData);
    }

    public function test_authenticate_returns_user_and_token_with_valid_credentials()
    {
        $credentials = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $this->userRepository
            ->shouldReceive('findByEmail')
            ->once()
            ->with($credentials['email'])
            ->andReturn($user);

        $result = $this->userService->authenticate($credentials);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertIsString($result['token']);
    }

    public function test_authenticate_throws_exception_with_invalid_credentials()
    {
        $credentials = [
            'email' => 'test_user_service_test@test_user_service_test.com',
            'password' => 'wrong_password'
        ];

        // Create user but Auth::attempt will fail due to wrong password
        User::factory()->create([
            'email' => 'test_user_service_test@test_user_service_test.com',
            'password' => Hash::make('correct_password')
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Credenciais Inválidas');
        $this->expectExceptionCode(401);

        $this->userService->authenticate($credentials);
    }

    public function test_logout_successfully_deletes_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token');

        $request = Request::create('/', 'POST');
        $request->headers->set('Authorization', 'Bearer ' . $token->plainTextToken);

        $result = $this->userService->logout($request);

        $this->assertTrue($result);

        // Verify token is deleted
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $token->accessToken->id
        ]);
    }

    public function test_logout_throws_exception_when_no_token_provided()
    {
        $request = Request::create('/', 'POST');
        // No Authorization header

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Token não fornecido');

        $this->userService->logout($request);
    }

    public function test_logout_throws_exception_when_invalid_token_provided()
    {
        $request = Request::create('/', 'POST');
        $request->headers->set('Authorization', 'Bearer invalid_token');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Token inválido');

        $this->userService->logout($request);
    }
}
