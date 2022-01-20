<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([]);

        $user = User::firstOrCreate([
            'is_admin' => true,
            'name' => 'Vladimir Gonchar',
            'email' => 'erriour.me@gmail.com',
            'avatar' => 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/68/682806cd580d0b9036bd4021bd6535d420f5e7ca_full.jpg',
            'password' => env('ADMIN_PASSWORD') ?? 'grjgiy9234t5ngrei',
        ]);
    }
}
