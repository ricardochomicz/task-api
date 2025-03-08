<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService
{
    /**
     * Retorna todas as tarefas do usuário logado. Ordenando por favoritos TRUE
     * 
     * @return array
     */
    public function index()
    {
        $tasks = Task::where('user_id', Auth::id())->orderBy('favorite', 'asc');
        return $tasks->paginate();
    }

    /**
     * Cria uma nova tarefa.
     * 
     * @param array $data
     * @return Task
     */
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

    public function show(Task $task)
    {
        return $task;
    }

    public function update(array $data, Task $task)
    {
        try {
            DB::beginTransaction();
            $task->update($data);
            DB::commit();
            return $task;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar a tarefa'], 500);
        }
    }

    public function destroy(Task $task)
    {
        try {
            DB::beginTransaction();
            $task->delete();
            DB::commit();
            return $task;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json(['error' => 'Erro ao deletar a tarefa'], 500);
        }
    }

    /**
     * Atualiza a cor de uma tarefa.
     */
    public function updateColor(Task $task, string $color)
    {
        $task->update(['color' => $color]);
        return $task;
    }

    /**
     * Atualiza o status de favorito de uma tarefa.
     */
    public function updateFavorite(Task $task, bool $favorite)
    {
        $task->update(['favorite' => $favorite]);
        return $task;
    }
}
