<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {   
        $tasks = Task::where('status', 1)->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.index', compact('tasks', 'categories'));
    }

    public function store(Request $request)
    {        
        $request->validate(['title' => 'required']);
        
        Task::create(['title' => $request->title]);
        
        $tasks = Task::where('status', 1)->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.task-list', compact('tasks', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update(['title' => $request->title]);
        
        $tasks = Task::where('status', 1)->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.task-list', compact('tasks', 'categories'));
        
    }

    public function destroy(Task $task)
    {
        $task->delete();
        
        $tasks = Task::where('status', 1)->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.task-list', compact('tasks', 'categories'));
    }

    public function markProgress(Request $request){
        $status = $request->input('status');
        $id = $request->input('id');
        Task::find($id)->update(['status' => $status]);

        $tasks = Task::where('status', 1)->latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.task-list', compact('tasks', 'categories'));
    }

    public function showAllTask(){
        $tasks = Task::latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('task.task-list', compact('tasks', 'categories'));
    }
}
