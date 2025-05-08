<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Homero Simpson',
            'email' => 'homero@springfield.com',
            'password' => bcrypt('password') // o Hash::make()
        ]);
    }
}
