<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function show(User $user)
    {
        return new UserResource($this->userService->show($user));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        return new UserResource($this->userService->update($request->validated(), $user));
    }
}
