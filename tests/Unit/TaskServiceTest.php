<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

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

    public function testShowTask()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $task = Task::factory()->create(['title' => 'Test Task', 'description' => 'Test Description', 'favorite' => false, 'color' => '#FFFFFF', 'user_id' => $user->id]);

        $task = $this->taskService->show($task);

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

        $updatedTask = $this->taskService->update($data, $task);

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

        $updatedTask = $this->taskService->updateColor($task, '#000000');

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

        $updatedTask = $this->taskService->updateFavorite($task, true);

        $this->assertTrue($updatedTask->favorite);
    }
}
