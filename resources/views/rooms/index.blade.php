@extends('layouts.app')

@section('title', 'Salles | SchoolGood')
@section('topbar_title', 'Salles')

@section('content')
    @include('partials.page-header', ['title' => 'Salles de cours', 'statLabel' => 'Total', 'statValue' => $rooms->total()])

    <section class="surface-card mt-6 p-5 lg:p-6">
        @can('create', \App\Models\Room::class)
            <a href="{{ route('rooms.create') }}" class="btn-primary mb-4 inline-flex">Nouvelle salle</a>
        @endcan
        <table class="data-table">
            <thead><tr><th>Nom</th><th>Bâtiment</th><th>Étage</th><th>Classes</th><th></th></tr></thead>
            <tbody>
                @foreach ($rooms as $room)
                    <tr>
                        <td>{{ $room->name }}</td>
                        <td>{{ $room->building ?: '—' }}</td>
                        <td>{{ $room->floor ?: '—' }}</td>
                        <td>{{ $room->classrooms_count }}</td>
                        <td><a href="{{ route('rooms.edit', $room) }}" class="btn-secondary">Modifier</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $rooms->links() }}
    </section>
@endsection
