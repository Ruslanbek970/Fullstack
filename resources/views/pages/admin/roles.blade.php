@extends('layouts.app')

@section('title', __('site.roles_page_title'))

@section('content')
    <h1 class="h4 mb-3">{{ __('site.roles_page_heading') }}</h1>

    @foreach($roles as $role)
        <div class="border border-secondary rounded p-3 mb-3 meme-card-surface">
            <div class="fw-bold text-warning">{{ __('site.role_labels.'.$role->name) !== 'site.role_labels.'.$role->name ? __('site.role_labels.'.$role->name) : $role->name }}</div>
            <div class="small mt-2">
                @forelse($role->permissions->pluck('name')->sort() as $perm)
                    <span class="role-perm-chip">{{ __('site.permission_labels.'.$perm) !== 'site.permission_labels.'.$perm ? __('site.permission_labels.'.$perm) : $perm }}</span>
                @empty
                    —
                @endforelse
            </div>

            <div class="mt-3">
                <div class="small text-secondary mb-2">{{ __('site.roles_users_in_role') }}</div>
                @if($role->name === 'user')
                    <span class="small text-secondary">{{ __('site.roles_user_role_hidden') }}</span>
                @else
                    @forelse($role->users as $u)
                        <span class="role-user-chip">
                            <img src="{{ $u->avatarUrl() }}" alt="">
                            <span>{{ $u->name }}</span>
                            <span class="text-secondary">· {{ $u->email }}</span>
                        </span>
                    @empty
                        <span class="small text-secondary">—</span>
                    @endforelse
                @endif
            </div>
        </div>
    @endforeach

    <h2 class="h6 mt-4">{{ __('site.roles_all_permissions') }}</h2>
    <div class="small">
        @foreach($permissions->pluck('name')->sort() as $perm)
            <span class="role-perm-chip">{{ __('site.permission_labels.'.$perm) !== 'site.permission_labels.'.$perm ? __('site.permission_labels.'.$perm) : $perm }}</span>
        @endforeach
    </div>
@endsection
