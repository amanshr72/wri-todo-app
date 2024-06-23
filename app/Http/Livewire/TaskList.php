<?php

namespace App\Http\Livewire;

use App\Models\Category;
use App\Models\Task;
use Livewire\Component;

class TaskList extends Component
{
    public $title;
    public $desc;
    public $status;

    public $showModal = false;
    public $taskIdToDelete;
    public $selectedTask;

    public $showUpdateModal = false;
    public $taskIdToUpdate;
    public $taskToUpdate = ['title' => '', 'category' => '', 'status' => '', 'description' => '',];

    // protected $listeners = ['openModal'];

    protected $rules = [
        'title' => 'required|string|max:255',
        'taskToUpdate.title' => 'required|string|max:255',
        'taskToUpdate.description' => 'nullable|string',
    ];

    public function openModal($taskId)
    {
        $this->taskIdToDelete = $taskId;
        $this->selectedTask = Task::find($taskId);
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedTask = null;
        $this->taskIdToDelete = null;
    }

    public function openUpdateModal($taskId)
    {
        $this->taskIdToUpdate = $taskId;
        $this->taskToUpdate = Task::findOrFail($taskId)->toArray();
        $this->showUpdateModal = true;
    }

    public function closeUpdateModal()
    {
        $this->showUpdateModal = false;
        $this->taskIdToUpdate = null;
        $this->resetValidation();
        $this->reset(['taskToUpdate']);
    }

    public function updateTask()
    {
        $this->validate();

        if ($this->taskIdToUpdate) {
            Task::find($this->taskIdToUpdate)->update($this->taskToUpdate);
        }

        $this->closeUpdateModal();
    }

    public function deleteTask($taskId)
    {
        $task = Task::find($taskId);
        if ($task) {
            $task->delete();
        }

        $this->closeModal();
    }



    public function addTask()
    {
        dd($this->title);
        $this->validate();

        Task::create([
            'title' => $this->title,
        ]);

        $this->reset('title');
    }

    public function render()
    {
        $tasks = Task::latest()->get();
        $categories = Category::orderBy('name')->get();
        return view('livewire.task-list', compact('tasks', 'categories'));
    }

}
