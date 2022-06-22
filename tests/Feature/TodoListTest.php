<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    public function test_fetch_all_todo_list()
    {
        // preparation / prepare
        TodoList::factory()->create(['name' => 'my list']);

        // action / perform
        $response = $this->getJson(route('todo-list'))->json();

        // assertion / predict
        $this->assertEquals(1, count($response));
        $this->assertEquals('my list', $response[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        // preparation / prepare
        $item = TodoList::factory()->create();

        // action / perform
        $response = $this->getJson(route('todo-list.show', $item->id))
            ->assertOk()
            ->json();

        // assertion / predict
        // $response->assertStatus(200);
        $this->assertEquals($response['name'], $item->name);
    }
}
