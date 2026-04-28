@extends('layouts.app')

@section('title', __('site.nav_profile'))

@section('content')
    <div class="mx-auto" style="max-width:720px">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h1 class="h4 mb-0">{{ __('site.nav_profile') }}</h1>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('account.permissions') }}">{{ __('site.account_permissions') }}</a>
        </div>

        <div class="row g-3">
            <div class="col-md-5">
                <div class="p-1 border border-secondary rounded meme-card-surface">
                    <div class="d-flex align-items-center gap-1 mb-2">
                        <img src="{{ $user->avatarUrl() }}" alt="" width="auto" height="50" class="rounded-circle border border-secondary">
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="small text-secondary">{{ $user->email }}</div>
                        </div>
                    </div>
                    <div class="small text-secondary">Можно загрузить аватар </div>
                </div>
            </div>
            <div class="col-md-8">
                <form action="{{ route('account.profile.update') }}" method="post" enctype="multipart/form-data" class="p-3 border border-secondary rounded meme-card-surface">
                    @csrf
                    @method('PATCH')

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                    @endif

                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.profile_name') }}</label>
                            <input type="text" name="name" class="form-control bg-body text-body border-secondary" value="{{ old('name', $user->name) }}" required maxlength="80">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">{{ __('site.profile_phone') }}</label>
                            <input type="text" name="phone" class="form-control bg-body text-body border-secondary" value="{{ old('phone', $user->phone) }}" maxlength="40" placeholder="+7…">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">{{ __('site.profile_email') }}</label>
                            <input type="email" name="email" class="form-control bg-body text-body border-secondary" value="{{ old('email', $user->email) }}" required maxlength="255">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">{{ __('site.profile_bio') }}</label>
                            <textarea name="bio" class="form-control bg-body text-body border-secondary" rows="4" maxlength="1000">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">{{ __('site.profile_avatar') }}</label>
                            <input type="file" name="avatar" class="form-control bg-body text-body border-secondary" accept="image/*,.webp">
                        </div>
                        
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">{{ __('site.profile_save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

