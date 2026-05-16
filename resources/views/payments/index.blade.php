@extends('layouts.app')

@section('title', 'Paiements | schoolGood')
@section('topbar_title', 'Paiements')

@section('content')
    <section class="page-hero" data-reveal>
        <div>
            <span class="page-hero__eyebrow">Finance scolaire</span>
            <h2 class="page-hero__title">Suivre les paiements avec une lecture plus claire des montants, types et statuts.</h2>
            <p class="page-hero__description">
                Les encaissements sont plus faciles a parcourir et a filtrer, aussi bien pour l administration que pour les parents.
            </p>
        </div>

        <div class="page-hero__aside">
            <div class="hero-stat">
                <p class="hero-stat__label">Paiements</p>
                <p class="hero-stat__value">{{ $payments->total() }}</p>
            </div>
            <div class="hero-stat">
                <p class="hero-stat__label">Lecture</p>
                <p class="hero-stat__value">Finance</p>
            </div>
        </div>
    </section>

    <section class="surface-card mt-6 p-5 lg:p-6" data-filter-scope data-reveal>
        <div class="toolbar">
            <div>
                <h2 class="section-title">Registre des paiements</h2>
                <p class="section-subtitle">Recherchez un eleve, un type de paiement ou un mode d encaissement.</p>
            </div>

            <div class="flex flex-wrap gap-3">
                <label class="search-shell">
                    <span class="search-shell__label">Recherche locale</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="Eleve, type, mode ou statut" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite]))
                    <a href="{{ route('payments.create') }}" class="btn-primary self-end">Nouveau paiement</a>
                @endif
            </div>
        </div>

        <div class="grid gap-4 md:hidden">
            @foreach ($payments as $payment)
                <article class="mobile-record" data-filterable-row>
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="mobile-record__title">{{ $payment->student?->full_name }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $payment->type?->label() }}</p>
                        </div>
                        <span class="badge">{{ $payment->status?->label() }}</span>
                    </div>

                    <div class="mobile-record__meta">
                        <p><span class="font-semibold text-slate-900">Montant:</span> {{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</p>
                        <p><span class="font-semibold text-slate-900">Mode:</span> {{ $payment->method?->label() }}</p>
                    </div>

                    <div class="record-actions">
                        <a href="{{ route('payments.show', $payment) }}" class="btn-secondary">Voir</a>
                        @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite]))
                            <a href="{{ route('payments.edit', $payment) }}" class="btn-secondary">Modifier</a>
                            <form method="POST" action="{{ route('payments.destroy', $payment) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce paiement ?')">
                                    Supprimer
                                </button>
                            </form>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="hidden md:block table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Eleve</th>
                        <th>Type</th>
                        <th>Montant</th>
                        <th>Mode</th>
                        <th>Reference</th>
                        <th>Statut</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($payments as $payment)
                        <tr data-filterable-row>
                            <td class="font-semibold text-slate-900">{{ $payment->student?->full_name }}</td>
                            <td>{{ $payment->type?->label() }}</td>
                            <td>{{ number_format((float) $payment->amount, 0, ',', ' ') }} FCFA</td>
                            <td>{{ $payment->method?->label() }}</td>
                            <td>{{ $payment->reference ?: '-' }}</td>
                            <td><span class="badge">{{ $payment->status?->label() }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('payments.show', $payment) }}" class="btn-secondary">Voir</a>
                                    @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Scolarite]))
                                        <a href="{{ route('payments.edit', $payment) }}" class="btn-secondary">Modifier</a>
                                        <form method="POST" action="{{ route('payments.destroy', $payment) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('Supprimer ce paiement ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            Aucun paiement ne correspond a cette recherche.
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </section>
@endsection
