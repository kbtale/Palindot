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
 * @SWG\Definition(
 *     definition="AuthController",
 *     type="object",
 *     description="This is the base API class",
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
     * @SWG\Post(
     *     path="/register",
     *     description="Registers a new user",
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="Registration parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/RegisterRequest")
     *     ),
     *     @SWG\Response(
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
     * @SWG\Post(
     *     path="/login",
     *     description="Logs in a user",
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="Login parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/LoginRequest")
     *     ),
     *     @SWG\Response(
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
     * @SWG\Post(
     *     path="/logout",
     *     description="Logs out a user",
     *     @SWG\Response(
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
