@extends('layouts.app')

@section('title', __('users.page_title') . ' | SchoolGood')
@section('topbar_title', __('nav.users'))

@section('content')
    @include('partials.page-header', [
        'title' => __('users.page_title'),
        'description' => __('users.page_desc'),
        'statLabel' => __('users.stat_label'),
        'statValue' => $users->total(),
    ])

    <x-content-panel class="mt-6" data-filter-scope :title="__('users.directory')" :subtitle="__('users.directory_subtitle')">
        <x-slot:toolbar>
            <div class="content-panel__toolbar">
                <form method="GET" class="flex flex-wrap items-end gap-3">
                    <label class="search-shell">
                        <span class="search-shell__label">{{ __('users.role_filter') }}</span>
                        <select name="role" class="field min-w-[14rem]">
                            <option value="">{{ __('users.all_roles') }}</option>
                            @foreach ($roles as $value => $label)
                                <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button type="submit" class="btn-secondary">{{ __('users.filter_btn') }}</button>
                </form>

                <label class="search-shell">
                    <span class="search-shell__label">{{ __('users.search_label') }}</span>
                    <input type="search" class="field min-w-[16rem]" placeholder="{{ __('users.search_placeholder') }}" data-table-search>
                </label>

                <a href="{{ route('users.create') }}" class="btn-primary self-end">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    {{ __('users.new_user') }}
                </a>
            </div>
        </x-slot:toolbar>

        <div class="overflow-x-auto table-shell">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>{{ __('users.col_name') }}</th>
                        <th>{{ __('users.col_email') }}</th>
                        <th>{{ __('users.col_phone') }}</th>
                        <th>{{ __('users.col_department') }}</th>
                        <th>{{ __('users.col_role') }}</th>
                        <th class="text-right">{{ __('ui.actions_col') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $managedUser)
                        @php
                            $roleBadge = match ($managedUser->role?->value) {
                                'fondateur'  => 'badge--violet',
                                'admin'      => 'badge--info',
                                'scolarite'  => 'badge--teal',
                                'enseignant' => 'badge--amber',
                                default      => '',
                            };
                        @endphp
                        <tr data-filterable-row>
                            <td>
                                <div class="table-name-cell">
                                    @if ($managedUser->photoURL)
                                        <img src="{{ $managedUser->photoURL }}" alt="{{ $managedUser->name }}" class="avatar" style="object-fit:cover;">
                                    @else
                                        <span class="avatar {{ $roleBadge ?: 'avatar--indigo' }}">
                                            {{ mb_strtoupper(mb_substr($managedUser->name, 0, 1)) }}
                                        </span>
                                    @endif
                                    <span class="font-semibold text-slate-900">{{ $managedUser->name }}</span>
                                </div>
                            </td>
                            <td class="text-slate-600 text-sm">{{ $managedUser->email }}</td>
                            <td class="text-slate-600">{{ $managedUser->phone ?: '—' }}</td>
                            <td class="text-slate-600">{{ $managedUser->department?->label() ?: '—' }}</td>
                            <td>
                                <span class="badge {{ $roleBadge }}">{{ $managedUser->role?->label() }}</span>
                            </td>
                            <td>
                                <div class="record-actions justify-end">
                                    <a href="{{ route('users.show', $managedUser) }}" class="btn-secondary">{{ __('ui.view') }}</a>
                                    @can('update', $managedUser)
                                        <a href="{{ route('users.edit', $managedUser) }}" class="btn-secondary">{{ __('ui.edit') }}</a>
                                    @endcan
                                    @can('delete', $managedUser)
                                        <form method="POST" action="{{ route('users.destroy', $managedUser) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger" onclick="return confirm(&quot;{{ __('users.delete_confirm') }}&quot;)">
                                                {{ __('ui.delete') }}
                                            </button>
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
                                    <p class="empty-state__title">{{ __('users.empty_title') }}</p>
                                    <p class="empty-state__desc">{{ __('users.empty_desc') }}</p>
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
            <p class="empty-state__desc">{{ __('users.no_match_desc') }}</p>
        </div>

        <div class="mt-6 pagination-wrap">
            {{ $users->links() }}
        </div>
    </x-content-panel>
@endsection
