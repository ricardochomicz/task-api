<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected TaskService $taskService
    ) {}

    /**
     * Display a listing of the resource.
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $search = $request->all();
        return TaskResource::collection($this->taskService->index($search));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        try {
            $newTask = $this->taskService->store($request->validated());
            return response()->json(['message' => 'Tarefa atualizado com sucesso!', 'task' => new TaskResource($newTask)], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Erro ao cadastrar tarefa.', 'details' => $th->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($this->taskService->show($task));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, int $task)
    {
        try {
            $updateTask = $this->taskService->update($request->validated(), $task);
            return response()->json(['message' => 'Tarefa atualizada com sucesso!', 'task' => new TaskResource($updateTask)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Erro ao atualizar tarefa.', 'details' => $th->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $task)
    {
        $this->taskService->destroy($task);
        return response()->json([], 204);
    }

    /**
     * Atualiza a cor de uma tarefa.
     *
     * @param Request $request
     * @param int $id
     * 
     */
    public function updateColor(Request $request, int $task)
    {
        try {
            $updateTask = $this->taskService->updateColor($request->input('color'), $task);
            return response()->json(['message' => 'Tarefa atualizada com sucesso!', 'task' => new TaskResource($updateTask)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Erro ao atualizar tarefa.', 'details' => $th->getMessage()], 500);
        }
    }

    /**
     * Atualiza o status de favorito de uma tarefa.
     *
     * @param Request $request
     * @param int $id
     */
    public function updateFavorite(Request $request, int $task)
    {
        $favorite = filter_var($request->input('favorite'), FILTER_VALIDATE_BOOLEAN);
        try {
            $updateTask = $this->taskService->updateFavorite($favorite, $task);
            return response()->json(['message' => 'Tarefa atualizada com sucesso!', 'task' => new TaskResource($updateTask)], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => 'Erro ao atualizar tarefa.', 'details' => $th->getMessage()], 500);
        }
    }
}
