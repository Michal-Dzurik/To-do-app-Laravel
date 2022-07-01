<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
