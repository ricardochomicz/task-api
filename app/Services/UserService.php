<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserService extends BaseService
{
    /**
     * @return User[]
     */
    public function index()
    {
        $users = User::where('id', Auth::id());
        return $users->get();
    }

    public function show(int $user)
    {
        return User::findOrFail($user);
    }

    public function update(array $data, int $user)
    {
        return $this->executeTransaction(function () use ($data, $user) {
            $us = $this->show($user);
            $us->update($data);
            return $us;
        });
    }
}
