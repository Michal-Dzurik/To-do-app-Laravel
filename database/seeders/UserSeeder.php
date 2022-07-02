<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
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
        User::create([
            'name' => 'Michal Dzurík',
            'email' => 'misko7104@gmail.com',
            'password' => Hash::make('22e3f2e3'),
        ]);

        User::create([
            'name' => 'Ignác Valejt',
            'email' => 'valejt.ignac@gmail.com',
            'password' => Hash::make('heslo1'),
        ]);

        User::create([
            'name' => 'Katarína Konečná',
            'email' => 'konecna.katarina.1999@gmail.com',
            'password' => Hash::make('kk1999'),
        ]);

        User::create([
            'name' => 'Roman Hraška',
            'email' => 'yablkos@brv.sk',
            'password' => Hash::make('brmbrm123'),
        ]);
    }
}
