<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="UserController",
 *     type="object",
 *     description="Class with all the functions needed to manage users.",
 * )
 */
class UserController extends ApiController
{
    /**
     * Display the information of the current user.
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/users",
     *     summary="Displays the information of the current user.",
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated user"
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user(); // Get the currently authenticated user

        return response()->json(new UserResource($user));
    }

    /**
     * Display the specified resource.
     *
     * @param User $user user
     *
     * @return JsonResponse
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Displays the information of the current user, too lol.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Specific user"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function show(User $user): JsonResponse
    {
        if (Auth::id() !== $user->id) { // Check if the authenticated user is the same as the user to be shown
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request request
     *
     * @return JsonResponse
     * @OA\Post(
     *     path="/users",
     *     summary="Creates a new user. Only available for the admin created via seeders.",
     *     @OA\RequestBody(
     *         description="User parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public static function store(UserRequest $request): JsonResponse {
        $loggedUser = auth()->user();
        if ( $loggedUser->name === 'admin') {
            $validated = $request->validated();
            $validated['password'] = bcrypt($request->password);
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password']
            ]);
    
            return response()->json([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'message' => 'User Created Successfully.'
            ]);
        } else {
            return response()->json(['error'=> 'You are not the admin, you must register users, not create them'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request request
     * @param User        $user    user
     *
     * @return JsonResponse
     * @OA\Put(
     *     path="/users/{id}",
     *     summary="Updates the current user.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\RequestBody(
     *         description="User parameters",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UserRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function update(UserUpdateRequest $request, User $user): JsonResponse
    {
        $loggedUser = auth()->user();
        if ($loggedUser->id === $user->id) {
            $user->update($request->validated());
            return response()->json([
                'message' => 'Data updated successfully',
            ]);
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the owner of this url address',
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user user
     *
     * @return JsonResponse
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Deletes the current user.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         schema={
     *             "type": "integer"
     *         }
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function destroy(User $user): JsonResponse
    {
        $loggedUser = auth()->user();

        if ($loggedUser->id === $user->id) {        
            $user->delete();
            return response()->json(
                ['message' => 'User deleted successfully']
            );
        } else {
            return response()->json([
                'message' => 'Unauthorized. You are not the owner of this url address',
            ], 403);
        }
    }
}
