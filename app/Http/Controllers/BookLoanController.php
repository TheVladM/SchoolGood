<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\BookLoan;
use App\Models\Student;
use App\Models\User;
use App\Services\BookLoanPenaltyService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class BookLoanController extends Controller
{
    public function __construct(private BookLoanPenaltyService $penaltyService) {}

    public function index(Request $request): View
    {
        $loans = $this->visibleLoansQuery($request->user())
            ->with(['book', 'student', 'user', 'issuedBy', 'returnedBy', 'penaltyPayment'])
            ->latest()
            ->paginate(10);

        foreach ($loans as $loan) {
            $this->penaltyService->syncOverdueDays($loan);
        }

        return view('book_loans.index', ['loans' => $loans]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create', BookLoan::class);

        return view('book_loans.create', [
            'books' => Book::orderBy('title')->get(),
            'students' => Student::with('classroom')->where('is_active', true)->orderBy('last_name')->get(),
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', BookLoan::class);

        $data = $this->normalizedData($request, $this->validatedData($request));

        BookLoan::create(array_merge($data, [
            'issued_by_id' => $request->user()->id,
        ]));

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Emprunt enregistre avec succes.');
    }

    public function show(Request $request, BookLoan $bookLoan): View
    {
        $this->authorize('view', $bookLoan);

        $bookLoan->load(['book', 'student', 'user', 'issuedBy', 'returnedBy']);

        return view('book_loans.show', ['loan' => $bookLoan]);
    }

    public function edit(Request $request, BookLoan $bookLoan): View
    {
        $this->authorize('update', $bookLoan);

        return view('book_loans.edit', [
            'loan' => $bookLoan,
            'books' => Book::orderBy('title')->get(),
            'students' => Student::with('classroom')->where('is_active', true)->orderBy('last_name')->get(),
            'teachers' => User::where('role', UserRole::Teacher->value)->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, BookLoan $bookLoan): RedirectResponse
    {
        $this->authorize('update', $bookLoan);

        $bookLoan->update($this->normalizedData($request, $this->validatedData($request), $bookLoan));

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Emprunt mis a jour avec succes.');
    }

    public function destroy(Request $request, BookLoan $bookLoan): RedirectResponse
    {
        $this->authorize('delete', $bookLoan);

        abort_if(
            blank($bookLoan->returned_at),
            422,
            'Veuillez d abord enregistrer le retour du livre.'
        );

        $bookLoan->delete();

        return redirect()
            ->route('book-loans.index')
            ->with('success', 'Emprunt supprime avec succes.');
    }

    public function returnLoan(Request $request, BookLoan $bookLoan): RedirectResponse
    {
        $this->authorize('return', $bookLoan);

        abort_if(
            filled($bookLoan->returned_at),
            422,
            'Le retour a deja ete enregistre pour cet emprunt.'
        );

        try {
            $bookLoan->update([
                'returned_at' => now()->toDateString(),
                'returned_by_id' => $request->user()->id,
            ]);

            return back()->with('success', 'Retour du livre enregistre avec succes.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement du retour. Veuillez réessayer.');
        }
    }

    public function chargePenalty(Request $request, BookLoan $bookLoan): RedirectResponse
    {
        $this->authorize('chargePenalty', $bookLoan);

        try {
            $payment = $this->penaltyService->createPenaltyPayment($bookLoan, $request->user());

            if (! $payment) {
                return back()->with('error', 'Aucune pénalité à facturer ou emprunteur non élève.');
            }

            return back()->with('success', 'Pénalité enregistrée comme paiement en attente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de l\'enregistrement de la pénalité. Veuillez réessayer.');
        }
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'book_id' => ['required', 'exists:books,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'user_id' => [
                'nullable',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', UserRole::Teacher->value)),
            ],
            'borrowed_at' => ['required', 'date'],
            'due_at' => ['nullable', 'date', 'after_or_equal:borrowed_at'],
            'daily_penalty_rate' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function normalizedData(Request $request, array $data, ?BookLoan $loan = null): array
    {
        if (blank($data['student_id'] ?? null) && blank($data['user_id'] ?? null)) {
            throw ValidationException::withMessages([
                'student_id' => 'Veuillez choisir soit un eleve, soit un enseignant emprunteur.',
            ]);
        }

        if (filled($data['student_id'] ?? null) && filled($data['user_id'] ?? null)) {
            throw ValidationException::withMessages([
                'user_id' => 'Un emprunt ne peut etre rattache qu a un seul emprunteur.',
            ]);
        }

        $book = Book::findOrFail($data['book_id']);
        $availableCopies = $book->availableCopies();

        if ($loan && blank($loan->returned_at) && $loan->book_id === $book->id) {
            $availableCopies++;
        }

        if ($availableCopies < 1) {
            throw ValidationException::withMessages([
                'book_id' => 'Aucun exemplaire disponible pour ce livre.',
            ]);
        }

        if (blank($data['due_at'] ?? null)) {
            $data['due_at'] = Carbon::parse($data['borrowed_at'])
                ->addDays($book->loan_duration_days)
                ->toDateString();
        }

        $data['daily_penalty_rate'] = $data['daily_penalty_rate'] ?? $book->late_fee_per_day;

        return $data;
    }

    private function authorizeBookLoanAccess(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
            UserRole::Teacher,
        ]);
    }

    private function authorizeBookLoanManagement(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }

    private function visibleLoansQuery(User $user): Builder
    {
        if ($user->hasRole(UserRole::Parent)) {
            return BookLoan::query()->whereHas('student', fn ($query) => $query->where('parent_id', $user->id));
        }

        if ($user->hasRole(UserRole::Teacher)) {
            return BookLoan::query()->where('user_id', $user->id);
        }

        return BookLoan::query();
    }

    private function ensureBookLoanVisible(User $user, BookLoan $bookLoan): void
    {
        abort_unless(
            $this->visibleLoansQuery($user)->whereKey($bookLoan->id)->exists(),
            403,
            'Vous ne pouvez pas consulter cet emprunt.'
        );
    }
}
