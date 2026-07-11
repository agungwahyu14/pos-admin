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
        User::create([
            'name' => 'Admin POS',
            'email' => 'admin@pos.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@pos.test',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        $this->call([
            DummyDataSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
