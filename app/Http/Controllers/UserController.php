<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display the information of the current user.
     *
     * @return JsonResponse
     * @SWG\Get(
     *     path="/users",
     *     description="Display the information of the current user",
     *     @SWG\Response(
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
     * @SWG\Get(
     *     path="/users/{id}",
     *     description="Display the information of the current user, too",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="Specific user"
     *     ),
     *     @SWG\Response(
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
     * @SWG\Post(
     *     path="/users",
     *     description="Creates a new user. Only available for the admin created via seeders",
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="User parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UserRequest")
     *     ),
     *     @SWG\Response(
     *         response=201,
     *         description="User created successfully"
     *     ),
     *     @SWG\Response(
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
     * @SWG\Put(
     *     path="/users/{id}",
     *     description="Updates the current user",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         name="request",
     *         in="body",
     *         description="User parameters",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/UserRequest")
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="User updated successfully"
     *     ),
     *     @SWG\Response(
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
     * @SWG\Delete(
     *     path="/users/{id}",
     *     description="Deletes the current user",
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the user",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="User deleted successfully"
     *     ),
     *     @SWG\Response(
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
