<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}
    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function index()
    {
        return UserResource::collection($this->userService->index());
    }


    /**
     * Display the specified resource.
     */
    public function show(int $user)
    {
        return new UserResource($this->userService->show($user));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $user)
    {
        try {
            $updatedUser = $this->userService->update($request->validated(), $user);
            return response()->json(['message' => 'Usuário atualizado com sucesso!', 'user' => new UserResource($updatedUser)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao atualizar usuário.', 'details' => $th->getMessage()], 500);
        }
    }

    public function me(Request $request)
    {

        return response()->json(new UserResource($request->user()));
    }
}
