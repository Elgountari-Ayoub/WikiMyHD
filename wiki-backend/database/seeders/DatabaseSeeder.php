<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Manual;
use App\Models\Space;
use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $password = Hash::make('admin');
        \App\Models\User::factory()->create([
            'name' => 'Khadija Arjane',
            'email' => 'khavetdigital@gmail.com',
            'post' => 'director',
            'password' => $password,
            'role' => 'admin',
            'status' => 1,
        ]);
    }
}
