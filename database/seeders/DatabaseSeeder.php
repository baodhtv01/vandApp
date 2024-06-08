<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(10)->create()->each(function ($user) {
            Store::factory(3)->create(['user_id' => $user->id])->each(function ($store) {
                Product::factory(10)->create(['store_id' => $store->id]);
            });
        });
    }
}
