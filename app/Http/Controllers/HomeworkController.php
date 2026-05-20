<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Homework::class);
        
        $homeworks = Homework::with(['teacher', 'classroom'])
            ->orderBy('due_date', 'desc')
            ->paginate(15);
        
        return view('homeworks.index', compact('homeworks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Homework::class);
        
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();
        
        return view('homeworks.create', compact('classrooms', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Homework::class);
        
        $data = $this->validatedData($request);
        
        Homework::create($data);
        
        return redirect()->route('homeworks.index')->with('success', 'Devoir créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Homework $homework)
    {
        $this->authorize('view', $homework);
        
        return view('homeworks.show', compact('homework'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Homework $homework)
    {
        $this->authorize('update', $homework);
        
        $classrooms = Classroom::all();
        $teachers = User::where('role', 'teacher')->get();
        
        return view('homeworks.edit', compact('homework', 'classrooms', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Homework $homework): RedirectResponse
    {
        $this->authorize('update', $homework);
        
        $data = $this->validatedData($request);
        
        $homework->update($data);
        
        return redirect()->route('homeworks.show', $homework)->with('success', 'Devoir mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Homework $homework): RedirectResponse
    {
        $this->authorize('delete', $homework);
        
        $homework->delete();
        
        return redirect()->route('homeworks.index')->with('success', 'Devoir supprimé avec succès');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'teacher_id' => 'required|exists:users,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'due_date' => 'required|date_format:Y-m-d H:i|after:now',
            'attachments' => 'nullable|array',
        ]);
    }
}
