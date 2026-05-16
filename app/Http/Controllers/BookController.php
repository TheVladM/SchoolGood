<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorizeBookAccess($request->user());

        $books = Book::query()
            ->with('managedBy')
            ->withCount(['activeLoans', 'loans'])
            ->latest()
            ->paginate(10);

        return view('books.index', ['books' => $books]);
    }

    public function create(Request $request): View
    {
        $this->authorizeBookManagement($request->user());

        return view('books.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorizeBookManagement($request->user());

        $data = $this->validatedData($request);
        $data['managed_by_id'] = $request->user()->id;

        Book::create($data);

        return redirect()
            ->route('books.index')
            ->with('success', 'Livre enregistre avec succes.');
    }

    public function show(Request $request, Book $book): View
    {
        $this->authorizeBookAccess($request->user());

        $book->load([
            'managedBy',
            'loans.student',
            'loans.user',
            'loans.issuedBy',
            'loans.returnedBy',
        ]);

        return view('books.show', ['book' => $book]);
    }

    public function edit(Request $request, Book $book): View
    {
        $this->authorizeBookManagement($request->user());

        return view('books.edit', ['book' => $book]);
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $this->authorizeBookManagement($request->user());

        $book->update($this->validatedData($request));

        return redirect()
            ->route('books.index')
            ->with('success', 'Livre mis a jour avec succes.');
    }

    public function destroy(Request $request, Book $book): RedirectResponse
    {
        $this->authorizeBookManagement($request->user());

        abort_if(
            $book->activeLoans()->exists(),
            422,
            'Impossible de supprimer un livre encore emprunte.'
        );

        $book->delete();

        return redirect()
            ->route('books.index')
            ->with('success', 'Livre supprime avec succes.');
    }

    private function validatedData(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'isbn' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'language' => ['nullable', 'string', 'max:255'],
            'total_copies' => ['required', 'integer', 'min:1'],
            'shelf_location' => ['nullable', 'string', 'max:255'],
            'loan_duration_days' => ['required', 'integer', 'min:1'],
            'late_fee_per_day' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'acquired_at' => ['nullable', 'date'],
        ]);
    }

    private function authorizeBookAccess(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
            UserRole::Teacher,
        ]);
    }

    private function authorizeBookManagement(User $user): void
    {
        $this->authorizeRoles($user, [
            UserRole::Founder,
            UserRole::Admin,
            UserRole::Scolarite,
        ]);
    }
}
