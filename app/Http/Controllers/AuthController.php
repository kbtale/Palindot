<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use \stdClass;

class AuthController extends Controller
{
    /**
     * New user registration
     *
     * @param RegisterRequest $request request
     *
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $validated['password'] = bcrypt($request->password);

        $user = User::create($validated);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'message' => 'User registered successfully.',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user),
            ]
        );
    }

    /**
     * Login user
     *
     * @param LoginRequest $request request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(
                ['message' => __('These credentials do not match')],
                406
            );
        }

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
            'message' => 'Hello! ' . $user->name,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
            ]
        );
    }

    /**
     * Deletes user access tokens
     *
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        //$user = Auth::user();
        //$user->tokens()->delete();
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Session closed successfully']);
    }
    
}
