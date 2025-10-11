<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('123'),
        ])->assignRole('Admin');

        User::create([
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('321'),
        ])->assignRole('Editor');

        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@example.com',
            'password' => bcrypt('cliente123'),
        ]);

        User::factory(9)->create();
    }
}
