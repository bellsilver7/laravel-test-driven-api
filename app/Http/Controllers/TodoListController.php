<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use Illuminate\Http\Request;

class TodoListController extends Controller
{
    public function index()
    {
        $list = TodoList::all();
        return response($list);
    }

    public function show($id)
    {
        $item = TodoList::find($id);
        return response($item);
    }
}
