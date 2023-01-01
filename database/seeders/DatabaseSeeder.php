<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Item::factory(20)->create();
        User::create(
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('12345678')
            ],

        );
        User::create(
            [
                'username' => 'user',
                'email' => 'user@gmail.com',
                'role' => 'customer',
                'password' => Hash::make('12345678')
            ],
        );
    }
}
