<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['username' => 'wahidah'],
            [
                'name' => 'Wahidah',
                'email' => 'wahidah@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('wahidahgaming12345'),
            ]
        );
    }
}
