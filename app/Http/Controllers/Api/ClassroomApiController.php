<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Http\Resources\ClassroomResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClassroomApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Classroom::query();

        // Filter by section
        if ($request->has('section')) {
            $query->where('section', $request->section);
        }

        // Filter by level
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classrooms = $query->with(['mainTeacher', 'languageTeacher'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => ClassroomResource::collection($classrooms),
            'meta' => [
                'total' => $classrooms->total(),
                'per_page' => $classrooms->perPage(),
                'current_page' => $classrooms->currentPage(),
                'last_page' => $classrooms->lastPage(),
            ],
        ]);
    }

    public function show(Classroom $classroom): JsonResponse
    {
        $classroom->load(['mainTeacher', 'languageTeacher']);

        return response()->json(new ClassroomResource($classroom));
    }
}
