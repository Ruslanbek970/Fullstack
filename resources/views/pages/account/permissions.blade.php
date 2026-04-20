@extends('layouts.app')

@section('title', __('site.permissions_title'))

@section('content')
    <div class="mx-auto" style="max-width:720px">
        <h1 class="h4 mb-3">{{ __('site.permissions_heading') }}</h1>
        <p class="small text-secondary">{{ __('site.permissions_intro') }}</p>

        <div class="card border-secondary mb-3 bg-body">
            <div class="card-body">
                <div class="fw-bold mb-2">{{ __('site.permissions_roles') }}</div>
                @forelse(auth()->user()->getRoleNames() as $role)
                    <span class="badge bg-secondary me-1">{{ __('site.role_labels.'.$role) !== 'site.role_labels.'.$role ? __('site.role_labels.'.$role) : $role }}</span>
                @empty
                    <span class="text-danger">{{ __('site.permissions_none') }}</span>
                @endforelse
            </div>
        </div>

        <div class="card border-secondary bg-body">
            <div class="card-body">
                <div class="fw-bold mb-2">{{ __('site.permissions_perms') }}</div>
                <div class="permissions-grid">
                    @foreach(auth()->user()->getAllPermissions()->pluck('name')->sort() as $perm)
                        <span class="perm-chip">
                            <span>✓</span>
                            <span>{{ __('site.permission_labels.'.$perm) !== 'site.permission_labels.'.$perm ? __('site.permission_labels.'.$perm) : $perm }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <p class="small text-secondary mt-3 mb-0">
            @can('meme.create')<a href="{{ route('memes.create') }}">{{ __('site.nav_create_meme') }}</a> · @endcan
            <a href="{{ route('memes.mine') }}">{{ __('site.nav_my_memes') }}</a>
            @can('meme.publish')
                · <a href="{{ route('memes.moderation') }}">{{ __('site.nav_moderation') }}</a>
            @endcan
            · <a href="{{ route('account.profile') }}">{{ __('site.nav_profile') }}</a>
        </p>
    </div>
@endsection
