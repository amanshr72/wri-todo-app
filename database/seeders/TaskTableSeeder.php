<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tasks = [
            [
                'title' => 'Check Errors',
                'description' => 'Description for Task 1',
            ],
            [
                'title' => 'Resolve Bugs',
                'description' => 'Description for Task 2',
            ],
            [
                'title' => 'Enchnace Functionality',
                'description' => 'Description for Task 3',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
