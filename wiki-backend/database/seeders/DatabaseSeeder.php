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
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'post' => 'director',
            'password' => $password,
            'role' => 'admin',
            'status' => 1,
        ]);

        $password = Hash::make('ayoub');
        User::factory()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'post' => 'Web developer',
            'password' => $password,
            'role' => 'user',
            'status' => 0,
        ]);

        $password = Hash::make('hamid');
        User::factory()->create([
            'name' => 'Hamid',
            'email' => 'hamid@gmail.com',
            'post' => 'Builder',
            'password' => $password,
            'role' => 'user',
            'status' => 0,
        ]);
        $password = Hash::make('pass2005');
        User::factory()->create([
            'name' => 'Azzden',
            'email' => 'azzdendrive@gmail.com',
            'post' => 'Student',
            'password' => $password,
            'role' => 'user',
            'status' => 0,
        ]);
        $password = Hash::make('ali1999');
        User::factory()->create([
            'name' => 'Ali',
            'email' => 'abdelalikhalif1999@gmail.com',
            'post' => 'Python developer',
            'password' => $password,
            'role' => 'user',
            'status' => 0,
        ]);

        // for ($i = 65; $i <= 90; $i++) {
        //     $letter = chr($i);
        //     Space::factory()->create([
        //         'title' => "$letter" . PHP_EOL,
        //         'description' => 'space description'
        //     ]);
        // }


        // $admin = User::find(1);
        // $admin->spaces()->syncWithoutDetaching([1 => ['is_creator' => true]]);

        // $ayoub = User::find(2);
        // $ayoub->spaces()->syncWithoutDetaching([1]);


        // Space::factory()->create([
        //     'title' => 'IT',
        //     'description' => 'IT description'
        // ]);

        // $admin = User::find(1);
        // $admin->spaces()->syncWithoutDetaching([2 => ['is_creator' => false]]);


        // Manual::factory()->create([
        //     'space_id' => 1,
        //     'title' => 'Backend',
        //     'description' => 'Backend description'
        // ]);

        // Article::factory()->create([
        //     'space_id' => 1,
        //     'manual_id' => 1,
        //     'title' => 'Laravel',
        // ]);
        // Article::factory()->create([
        //     'space_id' => 1,
        //     'manual_id' => 1,
        //     'title' => 'Tailwind',
        // ]);
        // Article::factory()->create([
        //     'space_id' => 1,
        //     'manual_id' => 1,
        //     'title' => 'Vuejs',
        // ]);
        // Article::factory()->create([
        //     'space_id' => 1,
        //     'manual_id' => 1,
        //     'title' => 'Mysql',
        // ]);

        // $admin = User::find(1);
        // $admin->manuals()->syncWithoutDetaching([1 => ['is_creator' => true]]);

        // $admin = User::find(1);
        // $admin->manuals()->syncWithoutDetaching([1 => ['is_creator' => true]]);

        // Manual::factory()->create([
            // 'space_id' => 1,
            // 'title' => 'manual 2',
            // 'description' => 'manual 2 description'
        // ]);

        // Manual::factory()->create([
            // 'space_id' => 2,
            // 'title' => 'manual 3',
            // 'description' => 'manual 2 description'
        // ]);
        // Manual::factory()->create([
            // 'space_id' => 2,
            // 'title' => 'manual 4',
            // 'description' => 'manual 2 description'
        // ]);
    }
}
