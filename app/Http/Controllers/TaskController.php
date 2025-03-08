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
    public function index()
    {
        return TaskResource::collection($this->taskService->index());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        return new TaskResource($this->taskService->store($request->validated()));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($this->taskService->show($task));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        return new TaskResource($this->taskService->update($request->validated(), $task));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
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
    public function updateColor(Request $request, Task $task)
    {
        return new TaskResource($this->taskService->updateColor($task, $request->input('color')));
    }

    /**
     * Atualiza o status de favorito de uma tarefa.
     *
     * @param Request $request
     * @param int $id
     */
    public function updateFavorite(Request $request, Task $task)
    {
        return new TaskResource($this->taskService->updateFavorite($task, $request->input('favorite')));
    }
}
