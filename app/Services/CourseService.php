<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Support\Collection;

class CourseService
{
    public function getStats(): array
    {
        $totalCourses = Course::count();

        $activeClassesCount = Course::where('status', 1)
            ->whereHas('classes')
            ->count();

        $upcomingCoursesCount = Course::where('status', 0)->count();

        $activeClasses = Course::where('status', 1)
            ->withSum('classes', 'max_students')
            ->get()
            ->pluck('classes_sum_max_students')
            ->filter()
            ->sum();

        $enrolledStudents = Course::where('status', 1)
            ->withCount(['classes' => function ($query) {
                $query->where('status', '!=', 3);
            }])
            ->get()
            ->sum(function ($course) {
                return $course->classes->sum(function ($class) {
                    return $class->enrollments()->where('status', 1)->count();
                });
            });

        $enrollmentRate = $activeClasses > 0
            ? round(($enrolledStudents / $activeClasses) * 100)
            : 0;

        return [
            'total_courses'          => $totalCourses,
            'active_classes_count'  => $activeClassesCount,
            'upcoming_courses_count' => $upcomingCoursesCount,
            'enrollment_rate'        => $enrollmentRate,
        ];
    }

    public function getList(array $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = Course::withCount('classes');

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('slug', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $query->where('price', '>=', (int) $filters['min_price']);
        }

        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $query->where('price', '<=', (int) $filters['max_price']);
        }

        if (isset($filters['status']) && in_array((int) $filters['status'], [0, 1, 2], true)) {
            $query->where('status', (int) $filters['status']);
        }

        $perPage = $filters['per_page'] ?? 10;

        return $query->orderByDesc('id')->paginate($perPage);
    }

    public function create(array $data): Course
    {
        return Course::create([
            'name'        => $data['name'],
            'slug'        => $data['slug'],
            'description' => $data['description'] ?? null,
            'price'       => $data['price'] ?? null,
            'thumbnail'   => $data['thumbnail'] ?? null,
            'status'      => 0,
        ]);
    }

    public function update(Course $course, array $data): Course
    {
        $filtered = array_filter($data, fn($value) => $value !== null);
        $course->update($filtered);

        return $course->fresh();
    }

    public function delete(Course $course): void
    {
        $course->delete();
    }
}
