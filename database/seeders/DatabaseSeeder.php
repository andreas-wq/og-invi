<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Akun admin untuk moderasi buku tamu (login: admin@gmail.com)
        User::factory()->create([
            'name' => 'Admin Undangan',
            'email' => 'admin@gmail.com',
            'is_admin' => true,
        ]);
    }
}
