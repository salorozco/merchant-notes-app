<?php

namespace Database\Seeders;

use App\Models\Merchant;
use App\Models\Note;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Merchant::all()->each(function ($merchant) {
            Note::factory()
                ->count(3)
                ->create([
                    'merchant_id' => $merchant->id,
                ]);
        });
    }
}
