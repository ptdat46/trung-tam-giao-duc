<?php

namespace App\Repositories;

use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TeacherRepository
{
    public function getClassesByTeacherId(int $teacherId): Collection
    {
        return ClassModel::with('course')
            ->where('teacher_id', $teacherId)
            ->orderByDesc('id')
            ->get();
    }

    public function getAllTeachers()
    {
        return User::where('role', 'teacher')->orderByDesc('id');
    }

    public function findTeacherById(int $id): ?User
    {
        return User::where('role', 'teacher')->find($id);
    }
}
