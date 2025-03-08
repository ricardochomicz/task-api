<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService extends BaseService
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
        return $this->executeTransaction(function () use ($data) {
            $data['user_id'] = Auth::id();
            $task = Task::create($data);
            return $task;
        });
    }

    public function show(int $task)
    {
        return Task::where('user_id', Auth::id())->findOrFail($task);
    }

    /**
     * Atualiza uma tarefa.
     * 
     * @param array $data
     * @param int $task
     * @return Task
     */
    public function update(array $data, int $task)
    {
        return $this->executeTransaction(function () use ($data, $task) {
            $tk = $this->show($task);
            $tk->update($data);
            return $tk;
        });
    }

    public function destroy(int $task)
    {
        return $this->executeTransaction(function () use ($task) {
            $tk = $this->show($task);
            $tk->delete();
            return true;
        });
    }

    /**
     * Atualiza a cor de uma tarefa.
     */
    public function updateColor(string $color, int $task)
    {
        return $this->executeTransaction(function () use ($color, $task) {
            $tk = $this->show($task);
            $tk->update(['color' => $color]);
            return $tk;
        });
    }

    /**
     * Atualiza o status de favorito de uma tarefa.
     */
    public function updateFavorite(bool $favorite, int $task)
    {
        return $this->executeTransaction(function () use ($favorite, $task) {
            $tk = $this->show($task);
            $tk->update(['favorite' => $favorite]);
            return $tk;
        });
    }
}
