@extends('layouts.app')

@section('title', __('site.nav_users'))

@section('content')
    <h1 class="h4 mb-3">{{ __('site.nav_users') }}</h1>

    <div class="table-responsive border border-secondary rounded">
        <table class="table table-striped table-sm mb-0 align-middle">
            <thead><tr><th>ID</th><th>{{ __('site.profile_name') }}</th><th>Email</th><th>{{ __('site.user_roles') }}</th><th>{{ __('site.user_ban') }}</th><th class="text-end">{{ __('site.col_actions') }}</th></tr></thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td>{{ $u->id }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $u->avatarUrl() }}" alt="" width="28" height="28" class="rounded-circle border border-secondary">
                                <div class="fw-semibold">{{ $u->name }}</div>
                            </div>
                        </td>
                        <td class="text-secondary">{{ $u->email }}</td>
                        <td>
                            @php($roleNames = $u->roles->pluck('name')->sort()->values())
                            <div class="small">
                                @if($roleNames->count() <= 1 && $roleNames->first() === 'user')
                                    <span class="text-secondary">—</span>
                                @else
                                    {{ $roleNames->reject(fn($r) => $r === 'user')->map(fn($r) => __('site.role_labels.'.$r) !== 'site.role_labels.'.$r ? __('site.role_labels.'.$r) : $r)->join(', ') ?: '—' }}
                                @endif
                            </div>
                            @can('role.manage')
                                @if($u->id !== auth()->id())
                                    <details class="mt-1">
                                        <summary class="small text-secondary">{{ __('site.user_roles_edit') }}</summary>
                                        <form action="{{ route('admin.users.roles', $u) }}" method="post" class="mt-1 d-flex gap-1 flex-wrap align-items-center">
                                            @csrf
                                            <select name="roles[]" class="form-select form-select-sm bg-body text-body border-secondary user-role-select" multiple>
                                                @foreach($roles as $r)
                                                    <option value="{{ $r->name }}" @selected($u->hasRole($r->name))>{{ __('site.role_labels.'.$r->name) !== 'site.role_labels.'.$r->name ? __('site.role_labels.'.$r->name) : $r->name }}</option>
                                                @endforeach
                                            </select>
                                            <button class="btn btn-sm btn-outline-warning">{{ __('site.user_roles_save') }}</button>
                                        </form>
                                        <div class="small text-secondary mt-1">{{ __('site.user_roles_hint') }}</div>
                                    </details>
                                @endif
                            @endcan
                        </td>
                        <td>{{ $u->banned_at ? $u->banned_at->format('Y-m-d H:i') : '—' }}</td>
                        <td class="text-end">
                            @can('user.ban')
                                @if($u->id !== auth()->id())
                                    @if($u->banned_at)
                                        <form action="{{ route('admin.users.unban', $u) }}" method="post" class="d-inline">@csrf<button class="btn btn-sm btn-outline-success">{{ __('site.user_unban') }}</button></form>
                                    @else
                                        <form action="{{ route('admin.users.ban', $u) }}" method="post" class="d-inline" onsubmit="return confirm(@json(__('site.user_ban_confirm')));">@csrf<button class="btn btn-sm btn-outline-danger">{{ __('site.user_ban_btn') }}</button></form>
                                    @endif
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
@endsection
