<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    /**
     * @return User[]
     */
    public function index()
    {
        $users = User::where('id', Auth::id());
        return $users->get();
    }
}
