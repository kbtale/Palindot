<?php

namespace App\Http\Controllers;

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

    
}
