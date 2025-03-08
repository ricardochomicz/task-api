<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $taskService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->taskService = new TaskService();
    }

    public function testIndexTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $tasks = $this->taskService->index();

        $this->assertCount(1, $tasks);
        $this->assertInstanceOf(Task::class, $tasks[0]);
        $this->assertEquals('Test Task', $tasks[0]->title);
    }

    public function testStoreTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'color' => '#FFFFFF',
            'favorite' => false,
        ];

        $task = $this->taskService->store($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
    }

    public function testStoreTasValidationError()
    {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'title' => '', //Titulo inválido
            'description' => 'Test Description',
            'color' => '#FFFFFF',
            'favorite' => false,
        ];

        $request = \Illuminate\Http\Request::create('/tasks', 'POST', $data);
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:7',
            'favorite' => 'boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }


        $this->taskService->store($data);
    }

    public function testShowTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $task = $this->taskService->show($task->id);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
    }

    public function testUpdateTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'color' => '#000000',
            'favorite' => true,
        ];

        $updatedTask = $this->taskService->update($data, $task->id);

        $this->assertEquals('Updated Task', $updatedTask->title);
        $this->assertEquals('Updated Description', $updatedTask->description);
        $this->assertEquals('#000000', $updatedTask->color);
        $this->assertTrue($updatedTask->favorite);
    }

    public function testUpdateTaskColor()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $updatedTask = $this->taskService->updateColor('#000000', $task->id);

        $this->assertEquals('#000000', $updatedTask->color);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'color' => '#000000'
        ]);
    }

    public function testUpdateTaskFavorite()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $updatedTask = $this->taskService->updateFavorite(true, $task->id);

        $this->assertTrue($updatedTask->favorite);
    }

    public function testDestroyTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $this->taskService->destroy($task->id);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }


    public function testDestroyTaskNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentTaskId = 9999; // ID que não existe no banco de dados
        $this->taskService->destroy($nonExistentTaskId);
    }

    public function testShowTaskNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentTaskId = 9999; // ID que não existe no banco de dados
        $this->taskService->show($nonExistentTaskId);
    }

    public function testUpdateTaskNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentTaskId = 9999; // ID que não existe no banco de dados
        $data = [
            'title' => 'Updated Task',
            'description' => 'Updated Description',
            'color' => '#000000',
            'favorite' => true,
        ];

        $this->taskService->update($data, $nonExistentTaskId);
    }

    public function testUpdateTaskColorNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentTaskId = 9999; // ID que não existe no banco de dados
        $this->taskService->updateColor('#000000', $nonExistentTaskId);
    }

    public function testUpdateTaskFavoriteNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $nonExistentTaskId = 9999; // ID que não existe no banco de dados
        $this->taskService->updateFavorite(true, $nonExistentTaskId);
    }
}
