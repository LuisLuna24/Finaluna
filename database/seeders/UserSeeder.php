<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Luis Luna',
            'email' => 'eduarlun4@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Hmcnjsa1*.'),
            'remember_token' => Str::random(10),
        ]);
    }
}
