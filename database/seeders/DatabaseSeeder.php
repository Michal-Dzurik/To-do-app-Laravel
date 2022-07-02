<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seed = new UserSeeder();

        $seed->run();

        $seed = new TaskSeeder();

        $seed->run();
    }
}
