<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->orderBy('favorite', 'asc')->get();
        return $tasks;
    }

    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $data['user_id'] = Auth::id();
            $task = Task::create($data);
            DB::commit();
            return $task;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['error' => 'Erro ao criar a tarefa'], 500);
        }
    }
}
