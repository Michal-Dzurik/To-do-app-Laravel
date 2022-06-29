<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Michal Dzurík',
            'email' => 'misko7104@gmail.com',
            'password' => Hash::make('22e3f2e3'),
        ]);

        DB::table('users')->insert([
            'name' => 'Ignác Valejt',
            'email' => 'valejt.ignac@gmail.com',
            'password' => Hash::make('heslo1'),
        ]);

        DB::table('users')->insert([
            'name' => 'Katarína Konečná',
            'email' => 'konecna.katarina.1999@gmail.com',
            'password' => Hash::make('kk1999'),
        ]);

        DB::table('users')->insert([
            'name' => 'Roman Hraška',
            'email' => 'yablko@brm.sk',
            'password' => Hash::make('brmbrm123'),
        ]);
    }
}
