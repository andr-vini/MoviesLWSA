<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class UserService
{

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function store(array $data): array
    {
        try {
            $created = $this->userRepository->create($data);
            $token = $created->createToken('access_token')->plainTextToken;

            return ['user' => $created, 'token' => $token];
        } catch (\Exception $e) {
            Log::error('Erro ao criar usuário', [
                'error_message' => $e->getMessage(),
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            throw new \RuntimeException('Erro na criação do usuário: ' . $e->getMessage());
        }
    }

    public function authenticate(array $data)
    {
        if (Auth::attempt($data)) {
            $user = $this->userRepository->findByEmail($data['email']);
            $token = $user->createToken('access_token')->plainTextToken;

            return ['user' => $user, 'token' => $token];
        } else {
            throw new \Exception("Credenciais Inválidas", 401);
        }
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->bearerToken();

            if (!$token) {
                throw new \Exception("Token não fornecido", 400);
            }

            $accessToken = PersonalAccessToken::findToken($token);

            if (!$accessToken) {
                throw new \Exception("Token inválido", 401);
            }

            $accessToken->delete();

            return true;
        } catch (\Exception $e) {
            Log::error('Erro no logout', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \RuntimeException($e->getMessage(), $e->getCode() ?: 500);
        }
    }
}
