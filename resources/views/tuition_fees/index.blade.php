@extends('layouts.app')

@section('title', 'Frais de scolarité | SchoolGood')
@section('topbar_title', 'Frais de scolarité')

@section('content')
    @include('partials.page-header', [
        'title' => 'Frais de scolarité',
        'description' => 'Grille par niveau et section (inscription + tranches).',
        'statValue' => $fees->total(),
        'statLabel' => 'Grilles',
    ])

    <section class="surface-card mt-6 p-5 lg:p-6" data-reveal>
        <div class="toolbar mb-4">
            @can('create', \App\Models\TuitionFee::class)
                <a href="{{ route('tuition-fees.create') }}" class="btn-primary">Nouvelle grille</a>
            @endcan
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Niveau</th>
                    <th>Section</th>
                    <th>Inscription</th>
                    <th>Total annuel</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($fees as $fee)
                    <tr>
                        <td>{{ $fee->level }}</td>
                        <td>{{ $fee->section?->label() }}</td>
                        <td>{{ number_format((float) $fee->registration_fee, 0, ',', ' ') }} F</td>
                        <td>{{ number_format($fee->totalAnnualFees(), 0, ',', ' ') }} F</td>
                        <td class="text-right">
                            <a href="{{ route('tuition-fees.show', $fee) }}" class="btn-secondary">Voir</a>
                            @can('update', $fee)
                                <a href="{{ route('tuition-fees.edit', $fee) }}" class="btn-secondary">Modifier</a>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-500">Aucune grille tarifaire.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">{{ $fees->links() }}</div>
    </section>
@endsection
