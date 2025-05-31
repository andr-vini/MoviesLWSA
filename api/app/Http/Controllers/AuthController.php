<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use App\Services\UserService;

class AuthController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    function login(LoginUserRequest $request)
    {
        try {
            $validated = $request->validated();

            $dataReturned = $this->userService->authenticate($validated);

            return response()->json([
                'access_token' => $dataReturned['token'],
                'token_type' => 'Bearer',
                'user' => $dataReturned['user']
            ]);
        } catch (\Exception $e) {
            if ($e->getCode() === 401) {
                return response()->json([
                    'message' => 'Credenciais Inválidas'
                ], 401);
            }

            return response()->json([
                'message' => 'Ocorreu um erro no servidor'
            ]);
        }
    }

    function register(RegisterUserRequest $request)
    {
        try {
            $data = $request->validated();
            $dataReturned = $this->userService->store($data);

            return response()->json([
                'access_token' => $dataReturned['token'],
                'token_type' => 'Bearer',
                'user' => $dataReturned['user'],
                'message' => 'Usuário cadastrado com sucesso'
            ], 201);
        } catch (\RuntimeException $e) {
            return response()->json([
                'error_message' => 'Ocorreu um erro ao tentar criar seu usuário'
            ], 500);
        }
    }

    function logout(Request $request)
    {
        try {
            $this->userService->logout($request);

            return response()->json([
                'message' => 'Logout realizado com sucesso.'
            ], 200);
        } catch (\RuntimeException $e) {
            return response()->json([
                'message' => 'Erro ao realizar logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
