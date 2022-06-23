<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::all();
        return response($list);
    }

    public function show(TodoList $list)
    {
        return response($list);
    }

    public function store(Request $request)
    {
        $list = TodoList::create($request->all());
        // return response($list, 201);
        // return response($list, Response::HTTP_CREATED);
        return $list;
    }
}
