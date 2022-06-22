<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_todo_list()
    {
        // preparation / prepare
        $lists = TodoList::factory()->create(['name' => 'my list']);
        // TodoList::create(['name' => 'my list']);

        // action / perform
        $response = $this->getJson(route('todo-list'));

        // assertion / predict
        $this->assertEquals(1, count($response->json()));
        $this->assertEquals('my list', $response->json()[0]['name']);
    }
}
