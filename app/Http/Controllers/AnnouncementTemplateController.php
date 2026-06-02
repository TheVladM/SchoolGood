<?php

namespace App\Http\Controllers;

use App\Enums\AnnouncementAudience;
use App\Enums\UserRole;
use App\Models\AnnouncementTemplate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnnouncementTemplateController extends Controller
{
    public function index(Request $request): View
    {
        abort_unless($request->user()->hasAnyRole([UserRole::Founder, UserRole::Admin, UserRole::Scolarite]), 403);

        $templates = AnnouncementTemplate::latest()->paginate(10);

        return view('announcement_templates.index', compact('templates'));
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->user()->hasAnyRole([UserRole::Founder, UserRole::Admin, UserRole::Scolarite]), 403);

        AnnouncementTemplate::create([
            ...$request->validate([
                'name' => ['required', 'string', 'max:255'],
                'title' => ['required', 'string', 'max:255'],
                'content' => ['required', 'string'],
                'audience' => ['nullable', Rule::enum(AnnouncementAudience::class)],
            ]),
            'created_by_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Modèle enregistré.');
    }

    public function destroy(AnnouncementTemplate $template): RedirectResponse
    {
        abort_unless(auth()->user()->hasAnyRole([UserRole::Founder, UserRole::Admin]), 403);

        $template->delete();

        return back()->with('success', 'Modèle supprimé.');
    }
}
