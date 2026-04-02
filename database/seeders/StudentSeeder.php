<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'student1@gmail.com'],
            [
                'name' => 'Học viên Test',
                'email' => 'student1@gmail.com',
                'password' => Hash::make('123123'),
                'phone' => '0901234567',
                'role' => 2,
                'status' => 1,
            ]
        );

        User::factory()
            ->count(99)
            ->state(['role' => 2, 'status' => 1])
            ->create();
    }
}
