<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Manual;
use App\Models\Space;
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
            'name' => 'Data Science',
            'email' => 'karjane@havetdigital.fr',
            'post' => 'Data scientist',
            'password' => $password,
            'role' => 'admin',
            'status' => 1,
        ]);

        $password = Hash::make('ayoub');
        \App\Models\User::factory()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'post' => 'Web developer',
            'password' => $password,
            'role' => 'user',
            'status' => 1,
        ]);

        Space::factory()->count(10)->create();
        Space::factory()->count(5)->create([
            'id_user' => 2 
        ]);
        Manual::factory()->count(5)->create();
        Manual::factory()->count(3)->create([
            'id_space' => 2,
            'id_user' => 2
        ]);
    }
}
