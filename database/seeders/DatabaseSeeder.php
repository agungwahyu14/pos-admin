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
        User::factory()->create([
            'name' => 'Admin POS',
            'email' => 'admin@pos.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.test',
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        $this->call([
            DummyDataSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
