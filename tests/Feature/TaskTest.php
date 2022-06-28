<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->authUser();
    }

    public function test_fetch_all_tasks_of_a_todo_list()
    {
        // preperation
        $list = $this->createTodoList();
        $list2 = $this->createTodoList();
        $task = $this->createTask(['todo_list_id' => $list->id]);
        $this->createTask(['todo_list_id' => $list2->id]);

        // action
        $response = $this->getJson(route('todo-list.task.index', $list->id))->assertOk()->json();

        // assertion
        $this->assertEquals(1, count($response));
        $this->assertEquals($task->title, $response[0]['title']);
        $this->assertEquals($response[0]['todo_list_id'], $list->id);
    }

    public function test_store_a_task_for_a_todo_list()
    {
        $list = $this->createTodoList();
        $task = $this->createTask();
        $this->postJson(route('todo-list.task.store', $list->id), ['title' => $task->title])
            ->assertCreated();
        $this->assertDatabaseHas('tasks', ['title' => $task->title, 'todo_list_id' => $list->id]);
    }

    public function test_delete_a_task_from_database()
    {
        $task = $this->createTask();
        $this->deleteJson(route('task.destroy', $task->id))->assertNoContent();
        $this->assertDatabaseMissing('tasks', ['title' => $task->title]);
    }

    public function test_update_a_task_of_a_todo_list()
    {
        $task = $this->createTask();
        $this->patchJson(route('task.update', $task->id), ['title' => 'updated title'])
            ->assertOk();
        $this->assertDatabaseHas('tasks', ['title' => 'updated title']);
    }
}
