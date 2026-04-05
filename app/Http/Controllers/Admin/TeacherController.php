<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Common;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Http\Resources\TeacherResource;
use App\Models\User;
use App\Repositories\TeacherRepository;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function __construct(
        private TeacherService $teacherService
    ) {}

    public function getClasses(int $id): JsonResponse
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $classes = $this->teacherService->getClassesByTeacherId($id);

        return Common::successResponse('Lấy danh sách lớp giáo viên thành công', [
            'teacher' => new TeacherResource($teacher),
            'classes' => $classes,
        ]);
    }
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 10);

        $teachers = User::where('role', 'teacher')
            ->orderByDesc('id')
            ->paginate($perPage);

        return Common::successResponse(
            'Lấy danh sách giáo viên thành công',
            [
                'data' => TeacherResource::collection($teachers),
                'meta' => [
                    'current_page' => $teachers->currentPage(),
                    'total'        => $teachers->total(),
                    'per_page'     => $teachers->perPage(),
                    'last_page'    => $teachers->lastPage(),
                ],
            ]
        );
    }

    public function show(int $id): JsonResponse
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        return Common::successResponse(
            'Lấy thông tin giáo viên thành công',
            ['teacher' => new TeacherResource($teacher)]
        );
    }

    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $createdTeachers = [];

        DB::transaction(function () use ($validated, &$createdTeachers) {
            foreach ($validated['teachers'] as $teacherData) {
                $teacher = User::create([
                    'name'     => $teacherData['name'],
                    'email'    => $teacherData['email'],
                    'password' => Hash::make($teacherData['password']),
                    'phone'    => $teacherData['phone'] ?? null,
                    'role'     => 'teacher',
                    'status'   => 1,
                ]);

                $createdTeachers[] = $teacher;
            }
        });

        return Common::successResponse(
            'Tạo giáo viên thành công',
            [
                'created_count' => count($createdTeachers),
                'teachers'      => TeacherResource::collection(collect($createdTeachers)),
            ],
            201
        );
    }

    public function update(UpdateTeacherRequest $request, int $id): JsonResponse
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);

        $validated = $request->validated();

        // Remove null fields so they don't override existing values
        $validated = array_filter($validated, fn($value) => $value !== null);

        $teacher->update($validated);

        return Common::successResponse(
            'Cập nhật giáo viên thành công',
            ['teacher' => new TeacherResource($teacher->fresh())]
        );
    }

    public function destroy(int $id): JsonResponse
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();

        return Common::successResponse('Xóa giáo viên thành công', []);
    }

    public function destroyMany(Request $request): JsonResponse
    {
        $request->validate([
            'ids'   => ['required', 'array', 'min:1'],
            'ids.*' => ['required', 'integer', 'exists:users,id'],
        ]);

        $deletedCount = User::where('role', 'teacher')
            ->whereIn('id', $request->ids)
            ->delete();

        return Common::successResponse('Xóa nhiều giáo viên thành công', [
            'deleted_count' => $deletedCount,
        ]);
    }
}
