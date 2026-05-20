<?php

namespace App\Http\Controllers;

use App\Enums\ClassroomSection;
use App\Enums\UserRole;
use App\Models\TuitionFee;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TuitionFeeController extends Controller
{
    public function index(Request $request): View
    {
        // Only founder can manage tuition fees
        abort_unless($request->user()->role === UserRole::Founder, 403);

        $fees = TuitionFee::query()
            ->with('managedBy')
            ->orderBy('level')
            ->orderBy('section')
            ->paginate(20);

        return view('tuition_fees.index', ['fees' => $fees]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', TuitionFee::class);

        return view('tuition_fees.create', [
            'sections' => ClassroomSection::options(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', TuitionFee::class);

        $data = $this->validatedData($request);
        $data['managed_by_id'] = $request->user()->id;

        TuitionFee::create($data);

        return redirect()
            ->route('tuition-fees.index')
            ->with('success', "Tarifs de frais de scolarité pour {$data['level']} créés avec succès.");
    }

    public function show(Request $request, TuitionFee $fee): View
    {
        $this->authorize('view', $fee);

        return view('tuition_fees.show', ['fee' => $fee]);
    }

    public function edit(Request $request, TuitionFee $fee): View
    {
        $this->authorize('update', $fee);

        return view('tuition_fees.edit', [
            'fee' => $fee,
            'sections' => ClassroomSection::options(),
        ]);
    }

    public function update(Request $request, TuitionFee $fee): RedirectResponse
    {
        $this->authorize('update', $fee);

        $data = $this->validatedData($request, $fee);

        $fee->update($data);

        return redirect()
            ->route('tuition-fees.index')
            ->with('success', "Tarifs de frais de scolarité pour {$data['level']} mis à jour avec succès.");
    }

    public function destroy(Request $request, TuitionFee $fee): RedirectResponse
    {
        $this->authorize('delete', $fee);

        $level = $fee->level;
        $fee->delete();

        return redirect()
            ->route('tuition-fees.index')
            ->with('success', "Tarifs de frais de scolarité pour {$level} supprimés avec succès.");
    }

    private function validatedData(Request $request, ?TuitionFee $fee = null): array
    {
        return $request->validate([
            'level' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tuition_fees', 'level')
                    ->where('section', $request->input('section'))
                    ->ignore($fee?->id),
            ],
            'section' => ['required', Rule::enum(ClassroomSection::class)],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'first_installment' => ['required', 'numeric', 'min:0'],
            'second_installment' => ['required', 'numeric', 'min:0'],
            'third_installment' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
    }
}
