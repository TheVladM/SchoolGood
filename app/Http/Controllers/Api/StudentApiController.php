<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Resources\StudentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Student::query();

        // Filter by classroom
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Filter by parent
        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        // Filter active/inactive
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('first_name', 'like', '%' . $request->search . '%')
                ->orWhere('last_name', 'like', '%' . $request->search . '%');
        }

        $students = $query->with(['classroom', 'parent'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => StudentResource::collection($students),
            'meta' => [
                'total' => $students->total(),
                'per_page' => $students->perPage(),
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
            ],
        ]);
    }

    public function show(Student $student): JsonResponse
    {
        $student->load(['classroom', 'parent']);

        return response()->json(new StudentResource($student));
    }
}
