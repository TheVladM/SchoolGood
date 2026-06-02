<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Room::class);

        $rooms = Room::withCount('classrooms')->orderBy('name')->paginate(15);

        return view('rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        $this->authorize('create', Room::class);

        return view('rooms.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Room::class);

        Room::create($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]));

        return redirect()->route('rooms.index')->with('success', 'Salle créée.');
    }

    public function edit(Room $room): View
    {
        $this->authorize('update', $room);

        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room): RedirectResponse
    {
        $this->authorize('update', $room);

        $room->update($request->validate([
            'name' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'floor' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
        ]));

        return redirect()->route('rooms.index')->with('success', 'Salle mise à jour.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        $this->authorize('delete', $room);

        abort_if($room->classrooms()->exists(), 422, 'Impossible de supprimer une salle affectée à une classe.');

        $room->delete();

        return redirect()->route('rooms.index')->with('success', 'Salle supprimée.');
    }
}
