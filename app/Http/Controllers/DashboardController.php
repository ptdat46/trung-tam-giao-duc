<?php

namespace App\Http\Controllers;

use App\Helpers\Common;
use App\Models\ClassModel;
use App\Models\Enrollment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getStats(): JsonResponse
    {
        $totalUsers = User::count();
        $currentMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $previousMonth = User::whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();
        $userGrowthPercent = $previousMonth > 0
            ? round((($currentMonth - $previousMonth) / $previousMonth) * 100)
            : ($currentMonth > 0 ? 100 : 0);

        $totalClasses = ClassModel::count();

        $totalCourses = Course::count();

        $activeClassesCount = ClassModel::where('status', 1)->count();

        $monthlyRevenue = Transaction::where('status', 1)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $revenueTarget = config('dashboard.revenue_target', 1500000000);
        $revenueTargetPercent = $revenueTarget > 0
            ? round(($monthlyRevenue / $revenueTarget) * 100)
            : 0;

        return Common::successResponse('Dashboard stats retrieved successfully', [
            'total_users'           => $totalUsers,
            'user_growth_percent'   => $userGrowthPercent,
            'total_classes'         => $totalClasses,
            'total_courses'         => $totalCourses,
            'active_classes_count'  => $activeClassesCount,
            'monthly_revenue'       => (int) $monthlyRevenue,
            'revenue_target_percent' => $revenueTargetPercent,
        ]);
    }

    public function getCharts(): JsonResponse
    {
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);

            $students = User::where('role', 'student')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $teachers = User::where('role', 'teacher')
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();

            $months[] = [
                'month'    => $month->format('M'),
                'students' => $students,
                'teachers' => $teachers,
            ];
        }

        return Common::successResponse('Chart data retrieved successfully', $months);
    }

    public function getStudentStatus(): JsonResponse
    {
        $pending  = Enrollment::where('status', 2)->count();
        $enrolled = Enrollment::where('status', 1)->count();
        $canceled = Enrollment::where('status', 0)->count();

        return Common::successResponse('Student status retrieved successfully', [
            'pending'  => $pending,
            'enrolled' => $enrolled,
            'canceled' => $canceled,
        ]);
    }

    public function getPendingActions(): JsonResponse
    {
        $pendingEnrollments = Enrollment::with([
            'user:id,name',
            'classModel:id,name,course_id',
            'classModel.course:id,name',
        ])
            ->where('status', 2)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $data = $pendingEnrollments->map(function ($enrollment) {
            $transaction = $enrollment->user->transactions()
                ->where('course_id', $enrollment->classModel->course_id)
                ->where('status', 1) // approved
                ->latest()
                ->first();

            return [
                'id'           => $enrollment->id,
                'student_name' => $enrollment->user->name,
                'course_name'  => $enrollment->classModel->course->name,
                'proof_image'  => $transaction?->proof_image,
                'amount'       => $transaction?->amount,
                'created_at'   => $enrollment->created_at->diffForHumans(),
            ];
        });

        return Common::successResponse('Pending actions retrieved successfully', $data);
    }
}
