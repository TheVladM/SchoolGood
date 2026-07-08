@extends('layouts.app')

@section('title', __('rooms.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.rooms'))

@section('content')
    @include('partials.page-header', [
        'title' => __('rooms.page_title'),
        'description' => __('rooms.page_desc'),
        'statLabel' => __('rooms.stat_label'),
        'statValue' => $rooms->total(),
    ])

    <x-content-panel class="mt-6" :title="__('rooms.directory')" :subtitle="__('rooms.directory_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                @can('create', \App\Models\Room::class)
                    <a href="{{ route('rooms.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('rooms.new_room') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('rooms.col_name') }}</th>
                        <th>{{ __('rooms.col_building') }}</th>
                        <th>{{ __('rooms.col_floor') }}</th>
                        <th>{{ __('rooms.col_classrooms') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $room->name }}</td>
                            <td class="text-slate-600">{{ $room->building ?: '—' }}</td>
                            <td class="text-slate-600">{{ $room->floor ?: '—' }}</td>
                            <td class="text-slate-700">{{ $room->classrooms_count }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('rooms.edit', $room) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                                    <p class="empty-state__title">{{ __('rooms.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('rooms.empty_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 pagination-wrap">{{ $rooms->links() }}</div>
    </x-content-panel>
@endsection
