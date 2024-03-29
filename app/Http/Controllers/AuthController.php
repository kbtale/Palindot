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

/**
 * @OA\Schema(
 *     schema="AuthController",
 *     type="object",
 *     description="Class with all the functions needed to register, login, logout and reset a user.",
 * )
 */
class AuthController extends ApiController
{
    /**
     * New user registration
     *
     * @param RegisterRequest $request request
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/register",
     *     summary="Registers a new user.",
     *     @OA\RequestBody(
     *         description="Registration parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully"
     *     )
     * )
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
            ], 201
        );
    }

    /**
     * Login user
     *
     * @param LoginRequest $request request
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/login",
     *     summary="Logs in a user.",
     *     @OA\RequestBody(
     *         description="Login parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User logged in successfully"
     *     )
     * )
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
     * @OA\Post(
     *     path="/logout",
     *     summary="Logs out a user.",
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully"
     *     )
     * )
     */
    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Session closed successfully']);
    }
    
}
