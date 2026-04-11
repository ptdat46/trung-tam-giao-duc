<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function __construct(
        private CourseService $courseService
    ) {}

    public function stats(): JsonResponse
    {
        $stats = $this->courseService->getStats();

        return Common::successResponse('Lấy thống kê khóa học thành công', $stats);
    }

    public function index(Request $request): JsonResponse
    {
        $courses = $this->courseService->getList($request->all());

        return Common::successResponse('Lấy danh sách khóa học thành công', [
            'data' => CourseResource::collection($courses),
            'meta' => [
                'current_page' => $courses->currentPage(),
                'total'       => $courses->total(),
                'per_page'    => $courses->perPage(),
                'last_page'   => $courses->lastPage(),
            ],
        ]);
    }

    public function store(StoreCourseRequest $request): JsonResponse
    {
        $course = $this->courseService->create($request->validated());

        return Common::successResponse('Tạo khóa học thành công', [
            'course' => new CourseResource($course),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $course = Course::withCount('classes')->findOrFail($id);

        return Common::successResponse('Lấy thông tin khóa học thành công', [
            'course' => new CourseResource($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, int $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $updated = $this->courseService->update($course, $request->validated());

        return Common::successResponse('Cập nhật khóa học thành công', [
            'course' => new CourseResource($updated),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $course = Course::findOrFail($id);
        $this->courseService->delete($course);

        return Common::successResponse('Xóa khóa học thành công', []);
    }
}
