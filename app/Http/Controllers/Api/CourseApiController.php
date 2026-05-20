<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Resources\CourseResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Course::query();

        // Filter by classroom
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Filter by teacher
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by day
        if ($request->has('day')) {
            $query->where('day', $request->day);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses = $query->with(['classroom', 'teacher'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => CourseResource::collection($courses),
            'meta' => [
                'total' => $courses->total(),
                'per_page' => $courses->perPage(),
                'current_page' => $courses->currentPage(),
                'last_page' => $courses->lastPage(),
            ],
        ]);
    }

    public function show(Course $course): JsonResponse
    {
        $course->load(['classroom', 'teacher']);

        return response()->json(new CourseResource($course));
    }
}
