@extends('layouts.app')

@section('title', __('classrooms.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.classrooms'))

@section('content')
    @include('partials.page-header', [
        'title' => __('classrooms.page_title'),
        'description' => __('classrooms.page_desc'),
        'statLabel' => __('ui.total'),
        'statValue' => $classrooms->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('classrooms.directory')" :subtitle="__('classrooms.directory_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('classrooms.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('classrooms.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\Classroom::class)
                    <a href="{{ route('classrooms.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('classrooms.new_classroom') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('classrooms.col_classroom') }}</th>
                        <th>{{ __('classrooms.col_level') }}</th>
                        <th>{{ __('classrooms.col_section') }}</th>
                        <th>{{ __('classrooms.col_teacher') }}</th>
                        <th>{{ __('classrooms.col_language') }}</th>
                        <th>{{ __('classrooms.col_count') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classrooms as $classroom)
                        <tr data-filterable-row>
                            <td>
                                <div class="table-name-cell">
                                    <span class="avatar {{ $classroom->section?->value === 'anglophone' ? 'avatar--violet' : 'avatar--teal' }}">
                                        {{ mb_strtoupper(mb_substr($classroom->name, 0, 2)) }}
                                    </span>
                                    <span class="font-semibold text-slate-900">{{ $classroom->name }}</span>
                                </div>
                            </td>
                            <td class="text-slate-600">{{ $classroom->level }}</td>
                            <td>
                                <span class="badge {{ $classroom->section?->value === 'anglophone' ? 'badge--violet' : 'badge--teal' }}">
                                    {{ $classroom->section?->label() }}
                                </span>
                            </td>
                            <td class="text-slate-600">{{ $classroom->mainTeacher?->name ?: '—' }}</td>
                            <td class="text-slate-600">{{ $classroom->languageTeacher?->name ?: '—' }}</td>
                            <td class="font-semibold text-slate-900">{{ $classroom->students_count }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('classrooms.show', $classroom) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $classroom)
                                        <a href="{{ route('classrooms.edit', $classroom) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                    @can('delete', $classroom)
                                        <form method="POST" action="{{ route('classrooms.destroy', $classroom) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('{{ __('classrooms.delete_confirm') }}')">
                                                {{ __('ui.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                                    <p class="empty-state__title">{{ __('classrooms.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('classrooms.empty_desc') }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="empty-state mt-4" data-filter-empty hidden>
            <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/></svg>
            <p class="empty-state__title">{{ __('ui.empty_title') }}</p>
            <p class="empty-state__desc">{{ __('classrooms.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $classrooms->links() }}
        </div>
    </x-content-panel>
@endsection
