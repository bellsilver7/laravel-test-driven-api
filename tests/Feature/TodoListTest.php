<?php

namespace Tests\Feature;

use App\Models\TodoList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoListTest extends TestCase
{
    use RefreshDatabase;

    private $list;
    public function setUp(): void
    {
        parent::setUp();
        $this->list = TodoList::factory()->create(['name' => 'my list']);
    }

    public function test_fetch_all_todo_list()
    {
        $response = $this->getJson(route('todo-list.index'))->json();
        $this->assertEquals(1, count($response));
        $this->assertEquals('my list', $response[0]['name']);
    }

    public function test_fetch_single_todo_list()
    {
        $response = $this->getJson(route('todo-list.show', $this->list->id))
            ->assertOk()
            ->json();
        $this->assertEquals($response['name'], $this->list->name);
    }

    public function test_store_new_todo_list()
    {
        // preperation
        $list = TodoList::factory()->make();

        // action
        $response = $this->postJson(route('todo-list.store'), ['name' => $list->name])
            // ->assertStatus(201);
            // ->assertSuccessful();
            ->assertCreated()
            ->json();

        // assertion
        $this->assertEquals($list->name, $response['name']);
        $this->assertDatabaseHas('todo_lists', ['name' => $list->name]);
    }

    public function test_while_storing_todo_list_name_field_is_required()
    {
        $this->withExceptionHandling();
        $response = $this->postJson(route('todo-list.store'))
            // ->assertStatus(422);
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}
