<?php

namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display the information of the current user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user(); // Get the currently authenticated user

        return response()->json(new UserResource($user)); // Use UserResource instead of UserResource
    }

    /**
     * Display the specified resource.
     *
     * @param      \App\Models\User  $User      The user to be shown
     *
     * @return     JsonResponse                     The json response.
     */
    public function show(User $user): JsonResponse
    {
        if (Auth::id() !== $user->id) { // Check if the authenticated user is the same as the user to be shown
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(new UserResource($user)); // Use UserResource instead of UserResource
    }

    /**
     * Function to create the user
     * 
     * @param UserRequest $request request
     *
     * @return JsonResponse
     */
    public static function store(UserRequest $request): JsonResponse {
        $validated = $request->validate();
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param      \App\Http\Requests\UserRequest  $request         The request
     * @param      \App\Models\User                $user            The subset to be updated
     *
     * @return     JsonResponse                                     The json response.
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $user->update($request->validated());
        return response()->json([
            'message' => 'Data updated successfully',
        ]);
    }

    /**
     * Destroys the given user.
     *
     * @param \App\Models\User $user The user
     *
     * @return JsonResponse         The json response.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return response()->json(
            ['message' => 'User deleted successfully']
        );
    }
}
