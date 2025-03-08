<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function index(): ResourceCollection
    {
        $tasks = $this->taskService->index();
        return new ResourceCollection($tasks);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $task = $this->taskService->store($request->validated());
            return new TaskResource($task);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => '[STORE] Erro ao criar a tarefa'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        try {
            return new TaskResource($task);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => '[SHOW] Erro ao localizar a tarefa'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        try {
            $task->update($request->validated());
            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao atualizar a tarefa'], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return response()->json([], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => '[DESTROY] Erro ao deletar a tarefa'], 500);
        }
    }

    /**
     * Atualiza a cor de uma tarefa.
     *
     * @param Request $request
     * @param int $id
     * 
     */
    public function updateColor(Request $request, Task $task)
    {
        try {
            $color = $request->input('color');
            $task->update(['color' => $color]);
            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['error' => '[UPDATECOLOR] Erro ao atualizar a tarefa'], 500);
        }
    }

    /**
     * Atualiza o status de favorito de uma tarefa.
     *
     * @param Request $request
     * @param int $id
     */
    public function updateFavorite(Request $request, Task $task)
    {
        try {
            $isFavorite = $request->input('favorite');
            $task->update(['favorite' => $isFavorite]);
            return new TaskResource($task);
        } catch (\Throwable $th) {
            return response()->json(['error' => '[UPDATEFAVORITE] Erro ao atualizar a tarefa'], 500);
        }
    }
}
