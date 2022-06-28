<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $this->postJson(route('user.register'), [
            'name' => 'Stanley',
            'email' => 'bellsilver7@gmail.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ])
            ->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'Stanley']);
    }
}
