<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Http\Requests\TodoRequest;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::all();
        return view('todo', compact('todos'));
    }

    public function store(TodoRequest $todoRequest)
    {
        $todo = Todo::create($todoRequest->validated());
        return response()->json([
            'success'=> true,
            'message' => 'Todo created',
            'data' => $todo
        ], 201);
    }

    public function update(Todo $todo)
    {
        $update = $todo->update([
            'completed' => true
        ]);
        return response()->json([
            'success'=> true,
            'message' => 'Todo updated',
            'data' => $update
        ], 200);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json([
            'success'=> true,
            'message' => 'Todo deleted'
        ], 200);
    }
}
