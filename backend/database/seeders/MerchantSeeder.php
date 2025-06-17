<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MerchantSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $users->each(function ($user) {
            Merchant::factory()
                ->count(3)
                ->create([
                    'user_id' => $user->getKey(),
                ]);
        });
    }
}
