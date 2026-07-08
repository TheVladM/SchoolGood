@extends('layouts.app')

@section('title', __('homeworks.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.homeworks'))

@section('content')
    @include('partials.page-header', [
        'title' => __('homeworks.page_title'),
        'description' => __('homeworks.page_desc'),
        'statLabel' => __('ui.total'),
        'statValue' => $homeworks->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('homeworks.registry')" :subtitle="__('homeworks.registry_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <label class="search-shell">
                    <span class="search-shell__label">{{ __('homeworks.search_label') }}</span>
                    <input type="search" class="field min-w-[18rem]" placeholder="{{ __('homeworks.search_placeholder') }}" data-table-search>
                </label>

                @if (auth()->user()->hasAnyRole([\App\Enums\UserRole::Founder, \App\Enums\UserRole::Admin, \App\Enums\UserRole::Teacher]))
                    <a href="{{ route('homeworks.create') }}" class="btn-primary self-end">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        {{ __('homeworks.new_homework') }}
                    </a>
                @endif
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('homeworks.col_title') }}</th>
                        <th>{{ __('homeworks.col_subject') }}</th>
                        <th>{{ __('homeworks.col_classroom') }}</th>
                        <th>{{ __('homeworks.col_teacher') }}</th>
                        <th>{{ __('homeworks.col_due') }}</th>
                        <th>{{ __('ui.status_col') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($homeworks as $homework)
                        @php
                            $status = $homework->status?->value ?? $homework->status;
                            $statusBadge = match ($status) {
                                'assigned'  => 'badge--warning',
                                'submitted' => 'badge--info',
                                'graded'    => 'badge--success',
                                default     => '',
                            };
                            $statusLabel = match ($status) {
                                'assigned'  => __('homeworks.status_assigned'),
                                'submitted' => __('homeworks.status_submitted'),
                                'graded'    => __('homeworks.status_graded'),
                                'closed'    => __('homeworks.status_closed'),
                                default     => ucfirst($status ?? '—'),
                            };
                        @endphp
                        <tr data-filterable-row>
                            <td>
                                <a href="{{ route('homeworks.show', $homework) }}" class="font-semibold text-slate-900 hover:text-indigo-600">
                                    {{ Str::limit($homework->title, 35) }}
                                </a>
                            </td>
                            <td class="text-slate-600">{{ $homework->subject ?? '—' }}</td>
                            <td><span class="badge badge--teal">{{ $homework->classroom->name }}</span></td>
                            <td class="text-slate-600">{{ $homework->teacher->name }}</td>
                            <td class="{{ $homework->isOverdue() ? 'text-rose-600 font-medium' : 'text-slate-600' }} text-sm">
                                {{ $homework->due_date->format('d/m/Y H:i') }}
                                @if ($homework->isOverdue())
                                    <span class="ml-1 text-xs">{{ __('homeworks.overdue') }}</span>
                                @endif
                            </td>
                            <td><span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span></td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('homeworks.show', $homework) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $homework)
                                        <a href="{{ route('homeworks.edit', $homework) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                    @can('delete', $homework)
                                        <form action="{{ route('homeworks.destroy', $homework) }}" method="POST" onsubmit="return confirm('{{ __('homeworks.delete_confirm') }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger">{{ __('ui.delete') }}</button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <svg class="empty-state__icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z"/></svg>
                                    <p class="empty-state__title">{{ __('homeworks.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('homeworks.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('homeworks.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $homeworks->links() }}
        </div>
    </x-content-panel>
@endsection
