@extends('layouts.app')

@section('title', __('students.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.students'))

@section('content')
    @include('partials.page-header', [
        'title' => __('students.page_title'),
        'description' => __('students.page_desc'),
        'statLabel' => __('ui.total'),
        'statValue' => $students->total(),
    ])

    <x-content-panel class="mt-1" data-filter-scope :title="__('students.directory')" :subtitle="__('students.directory_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <label class="search-shell">
                        <span class="search-shell__label">{{ __('students.filter_status') }}</span>
                        <select name="status_scope" class="field min-w-[12rem]">
                            <option value="active"   @selected($statusScope === 'active')>{{ __('students.status_active') }}</option>
                            <option value="archives" @selected($statusScope === 'archives')>{{ __('students.status_archived') }}</option>
                            <option value="all"      @selected($statusScope === 'all')>{{ __('students.status_all') }}</option>
                        </select>
                    </label>
                    <label class="search-shell">
                        <span class="search-shell__label">{{ __('students.filter_school_year') }}</span>
                        <select name="school_year_id" class="field min-w-[14rem]">
                            <option value="">{{ __('students.all_years') }}</option>
                            @foreach ($schoolYears as $schoolYear)
                                <option value="{{ $schoolYear->id }}" @selected((string) request('school_year_id') === (string) $schoolYear->id)>
                                    {{ $schoolYear->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn-secondary">{{ __('ui.filter') }}</button>
                </form>

                <label class="search-shell">
                    <span class="search-shell__label">{{ __('students.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('students.search_placeholder') }}" data-table-search>
                </label>

                @can('create', \App\Models\Student::class)
                    <a href="{{ route('students.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('students.new_student') }}
                    </a>
                @endcan
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('students.col_student') }}</th>
                        <th>{{ __('students.col_classroom') }}</th>
                        <th>{{ __('students.col_parent') }}</th>
                        <th>{{ __('ui.status_col') }}</th>
                        <th>{{ __('students.col_birth') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        <tr data-filterable-row>
                            <td>
                                <div class="table-name-cell">
                                    <span class="avatar avatar--indigo">{{ mb_strtoupper(mb_substr($student->first_name, 0, 1)).mb_strtoupper(mb_substr($student->last_name, 0, 1)) }}</span>
                                    <span class="font-semibold text-slate-900">{{ $student->full_name }}</span>
                                </div>
                            </td>
                            <td>{{ $student->classroom?->name ?? '—' }}</td>
                            <td class="text-slate-600">{{ $student->parent?->name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $student->is_active ? 'badge--success' : '' }}">
                                    {{ $student->is_active ? __('students.active') : __('students.archived') }}
                                </span>
                            </td>
                            <td class="text-slate-600">{{ $student->birth_date?->format('d/m/Y') }}</td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('students.show', $student) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $student)
                                        <a href="{{ route('students.edit', $student) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                    @can('delete', $student)
                                        <form method="POST" action="{{ route('students.destroy', $student) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm('{{ __('students.delete_confirm') }}')">{{ __('ui.delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                    <p class="empty-state__title">{{ __('students.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('students.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('students.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $students->links() }}
        </div>
    </x-content-panel>
@endsection
