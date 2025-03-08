<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function show(User $user)
    {
        return $user;
    }

    public function update(array $data, User $user)
    {
        try {
            DB::beginTransaction();
            $user->update($data);
            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar usuário'], 500);
        }
    }
}
