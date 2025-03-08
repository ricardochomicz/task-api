<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserServiceTest extends TestCase
{

    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $this->userService = new UserService();
    }

    public function testIndexUser()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $users = $this->userService->index();

        $this->assertCount(1, $users);
        $this->assertInstanceOf(User::class, $users[0]);
        $this->assertEquals($user->name, $users[0]->name);
    }

    public function testShowUser()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $user = $this->userService->show($user->id);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($user->name, $user->name);
    }

    public function testUpdateUser()
    {
        $user = User::factory()->create();
        Auth::login($user);

        $data = [
            'name' => 'Updated Name',
            'email' => 'email@example.com',
        ];

        $updatedUser = $this->userService->update($data, $user->id);

        $this->assertEquals('Updated Name', $updatedUser->name);
        $this->assertEquals('email@example.com', $updatedUser->email);
    }

    public function testShowUserNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->userService->show(9999);
    }

    public function testUpdateUserNotFound()
    {
        $this->expectException(ModelNotFoundException::class);

        $user = (int) 999;

        $this->userService->update(['name' => 'Novo Nome', 'email' => 'email@test.com'], $user);
    }
}
