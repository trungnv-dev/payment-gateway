<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::whereNotIn('id', [1, 2])->delete();
        // \App\Models\User::factory(100000)->create();
        \App\Models\User::create([
            'role' => UserRole::ADMIN,
            'name' => 'ADMIN',
            'email' => 'admin@gmail.com',
            'password' => 'Trungtka123$',
        ]);
    }
}
