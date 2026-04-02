<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'GV Nguyễn Văn A', 'email' => 'giaovienA@gmail.com'],
            ['name' => 'GV Trần Thị B', 'email' => 'giaovienB@gmail.com'],
            ['name' => 'GV Lê Hoàng C', 'email' => 'giaovienC@gmail.com'],
            ['name' => 'GV Phạm Minh D', 'email' => 'giaovienD@gmail.com'],
            ['name' => 'GV Hoàng Thu E', 'email' => 'giaovienE@gmail.com'],
        ];

        foreach ($teachers as $teacher) {
            User::firstOrCreate(
                ['email' => $teacher['email']],
                [
                    'name' => $teacher['name'],
                    'email' => $teacher['email'],
                    'password' => Hash::make('123123'),
                    'role' => 1,
                ]
            );
        }
    }
}
