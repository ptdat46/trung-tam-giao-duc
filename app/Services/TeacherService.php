<?php

namespace App\Services;

use App\Repositories\TeacherRepository;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository
    ) {}

    public function getClassesByTeacherId(int $teacherId): array
    {
        $classes = $this->teacherRepository->getClassesByTeacherId($teacherId);

        return $classes->map(function ($class) {
            $totalSessions = $class->sessions()->count();
            $totalStudents = $class->enrollments()->where('status', 1)->count();

            return [
                'id'            => $class->id,
                'name'          => $class->name,
                'type'          => $class->type,
                'type_label'    => $class->type === 0 ? 'Offline' : 'Online',
                'room_number'   => $class->room_number,
                'meeting_link'  => $class->meeting_link,
                'start_date'    => $class->start_date,
                'end_date'      => $class->end_date,
                'status'        => $class->status,
                'status_label'  => $this->classStatusLabel($class->status),
                'course'        => $class->course ? [
                    'id'    => $class->course->id,
                    'name'  => $class->course->name,
                    'slug'  => $class->course->slug,
                ] : null,
                'total_sessions'  => $totalSessions,
                'total_students'  => $totalStudents,
                'created_at'    => $class->created_at,
            ];
        })->toArray();
    }

    private function classStatusLabel(int $status): string
    {
        return match ($status) {
            0 => 'Sắp mở',
            1 => 'Đang học',
            2 => 'Đã kết thúc',
            3 => 'Đã hủy',
            default => 'Không xác định',
        };
    }
}
