<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Homework;
use App\Http\Resources\HomeworkResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeworkApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Homework::class);

        $query = Homework::query();

        // Filter by classroom
        if ($request->has('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }

        // Filter by teacher
        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $homeworks = $query->with(['teacher', 'classroom'])
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => HomeworkResource::collection($homeworks),
            'meta' => [
                'total' => $homeworks->total(),
                'per_page' => $homeworks->perPage(),
                'current_page' => $homeworks->currentPage(),
                'last_page' => $homeworks->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Homework::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'teacher_id' => 'required|exists:users,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'due_date' => 'required|date_format:Y-m-d\TH:i:s',
            'attachments' => 'nullable|array',
        ]);

        $homework = Homework::create($validated);

        return response()->json(new HomeworkResource($homework), 201);
    }

    public function show(Homework $homework): JsonResponse
    {
        $this->authorize('view', $homework);

        return response()->json(new HomeworkResource($homework));
    }

    public function update(Request $request, Homework $homework): JsonResponse
    {
        $this->authorize('update', $homework);

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'teacher_id' => 'exists:users,id',
            'classroom_id' => 'exists:classrooms,id',
            'due_date' => 'date_format:Y-m-d\TH:i:s',
            'status' => 'in:assigned,submitted,graded',
            'attachments' => 'nullable|array',
        ]);

        $homework->update($validated);

        return response()->json(new HomeworkResource($homework));
    }

    public function destroy(Homework $homework): JsonResponse
    {
        $this->authorize('delete', $homework);

        $homework->delete();

        return response()->json(['message' => 'Homework deleted successfully'], 200);
    }
}
