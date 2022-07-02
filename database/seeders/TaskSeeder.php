<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $task = Task::create([
            'title' => 'Do the laundry',
            'description' => 'lorem ipsum',
            'category' => 'Home'
        ]);

        $task->users()->attach([1]);

        $task = Task::create([
            'title' => 'Go to store',
            'description' => 'lorem ipsum',
            'category' => 'Groceries'
        ]);

        $task->users()->attach([1]);

        $task = Task::create([
            'title' => 'Go to store',
            'description' => 'lorem ipsum',
            'category' => 'Groceries'
        ]);

        $task->users()->attach([2]);

        $task = Task::create([
            'title' => 'Play COD',
            'description' => 'lorem ipsum',
            'category' => 'Games'
        ]);

        $task->users()->attach([2]);
    }
}
