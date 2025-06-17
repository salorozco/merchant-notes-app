<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'), // known password
        ]);

        User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $this->call(
            MerchantSeeder::class,
            NoteSeeder::class
        );

    }
}
